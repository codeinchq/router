<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material  is strictly forbidden unless prior   |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     13/02/2018
// Time:     13:06
// Project:  lib-router
//
namespace CodeInc\Router;
use CodeInc\Router\Exceptions\CallableInvalidResponseException;
use CodeInc\Router\Exceptions\ExistingRouteException;
use CodeInc\Router\Exceptions\InvalidTargetException;
use CodeInc\Router\Exceptions\InexistantNotFoundRouteException;
use CodeInc\Router\Exceptions\RequestProcessingException;
use CodeInc\Router\Exceptions\RouteNotFoundException;
use CodeInc\Router\Exceptions\RouterException;
use CodeInc\Router\Exceptions\UnknownTargetTypeException;
use CodeInc\Router\Interfaces\RoutableInterface;
use CodeInc\Router\Request\Request;
use CodeInc\Router\Response\ResponseInterface;


/**
 * Class Router
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class Router implements RouterInterface {
	/**
	 * List of pages URIs (keys) matching pages classes (values)
	 *
	 * @var array|RoutableInterface[]
	 */
	private $routes = [];

	/**
	 * @var RouterInterface[]
	 */
	private $subRouters = [];

	/**
	 * Not found route.
	 *
	 * @var string|null
	 */
	private $notFoundRoute;

	/**
	 * Sets the not found route.
	 *
	 * @param string $route
	 * @throws InexistantNotFoundRouteException
	 */
	public function setNotFoundRoute(string $route):void {
		if (!isset($this->routes[$route])) {
			throw new InexistantNotFoundRouteException($route, $this);
		}
		$this->notFoundRoute = $route;
	}

	/**
	 * Adds a route.
	 *
	 * @param string $route Route (can be a pattern compatible with fnmatch())
	 * @param string|RoutableInterface|callable $target Callable, class or object implementing RoutableInterface
	 * @throws InvalidTargetException
	 * @throws ExistingRouteException
	 */
	public function addRoute(string $route, $target) {
		if (!is_callable($target) || !is_subclass_of($target, RoutableInterface::class)) {
			throw new InvalidTargetException($target, $this);
		}
		if (isset($this->routes[$route])) {
			throw new ExistingRouteException($route, $this);
		}
		$this->routes[$route] = $target;
		if ($target instanceof RouterInterface) {
			$this->subRouters[$route] = $target;
		}
	}

	/**
	 * @inheritdoc
	 * @param resource $request
	 * @return bool
	 */
	public function hasRoute(Request $request):bool {
		return $this->getRequestRoute($request) !== null;
	}

	/**
	 * Returns the route for a given request.
	 *
	 * @param Request $request
	 * @param bool|null $allowNotFound
	 * @return null|string
	 */
	protected function getRequestRoute(Request $request, bool $allowNotFound = null):?string {
		$route = $request->getUrl()->getPath();

		// if there is a matching route
		if (isset($this->routes[$route])) {
			return $route;
		}

		// if a registered route pattern is compatible with the request routed
		foreach ($this->routes as $registeredRoute => $target) {
			if (fnmatch($registeredRoute, $route) && (!($target instanceof RouterInterface) || $target->hasRoute($target))) {
				return $registeredRoute;
			}
		}

		// if a not found route is registered
		if ($allowNotFound !== false && $this->notFoundRoute) {
			return $this->notFoundRoute;
		}

		return null;
	}

	/**
	 * Returns the target for a given request.
	 *
	 * @param Request $request
	 * @param bool|null $allowNotFound
	 * @return string|RoutableInterface|callable|null
	 */
	protected function getRequestTarget(Request $request, bool $allowNotFound = null) {
		if (($route = $this->getRequestRoute($request, $allowNotFound)) !== null) {
			return $this->routes[$route];
		}
		return null;
	}

	/**
	 * @inheritdoc
	 * @param Request $request
	 * @param bool|null $allowNotFound
	 * @return ResponseInterface
	 * @throws RouteNotFoundException
	 * @throws RouterException
	 * @throws UnknownTargetTypeException
	 */
	public function process(Request $request, bool $allowNotFound = null):ResponseInterface {
		// getting the target
		if (($target = $this->getRequestTarget($request, $allowNotFound)) === null) {
			throw new RouteNotFoundException($request, $this);
		}

		// if the target is a class
		if (is_string($target)) {
			return $this->processClass($target, $request);
		}

		// if the target is an object
		if ($target instanceof RoutableInterface) {
			return $this->processObject($target, $request);
		}

		// if the target is callable
		else if (is_callable($target)) {
			return $this->processCallable($target, $request);
		}

		// if the target is something unknown.
		throw new UnknownTargetTypeException($target, $this);
	}

	/**
	 * Processes a class.
	 *
	 * @param string $class
	 * @param Request $request
	 * @return ResponseInterface
	 * @throws RequestProcessingException
	 */
	public function processClass(string $class, Request $request):ResponseInterface {
		try {
			/** @var RoutableInterface $object */
			$object = new $class($this);
			return $object->process($request);
		}
		catch (\Throwable $exception) {
			throw new RequestProcessingException(
				$request, $this,
				"Error while processing the request \"{$request->getUrl()}\" using the class \"$class\"",
				$exception
			);
		}
	}

	/**
	 * Processes the object.
	 *
	 * @param RoutableInterface $object
	 * @param Request $request
	 * @return ResponseInterface
	 * @throws RequestProcessingException
	 */
	private function processObject(RoutableInterface $object, Request $request):ResponseInterface {
		try {
			return $object->process($request);
		}
		catch (\Throwable $exception) {
			throw new RequestProcessingException(
				$request, $this,
				"Error while processing the request \"{$request->getUrl()}\" using the object "
					."\"".get_class($object)."\"",
				$exception
			);
		}
	}

	/**
	 * Processses a callable target.
	 *
	 * @param callable $callable
	 * @param Request $request
	 * @return mixed
	 * @throws RequestProcessingException
	 */
	private function processCallable(callable $callable, Request $request):ResponseInterface {
		try {
			$response = $callable($request);
			if ($response instanceof ResponseInterface) {
				throw new CallableInvalidResponseException($this);
			}
			return $response;
		}
		catch (\Throwable $exception) {
			throw new RequestProcessingException(
				$request, $this,
				"Error while processing the request \"{$request->getUrl()}\" using a callable",
				$exception
			);
		}
	}
}