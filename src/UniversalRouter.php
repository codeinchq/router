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
use CodeInc\Router\Exceptions\ExistingRouteException;
use CodeInc\Router\Exceptions\RouterException;
use CodeInc\Router\Interfaces\RoutableInterface;
use CodeInc\Router\Request\Request;
use CodeInc\Router\Response\ResponseInterface;


/**
 * Class UniversalRouter
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class UniversalRouter implements RouterInterface {
	/**
	 * List of pages URIs (keys) matching pages classes (values)
	 *
	 * @var array
	 */
	private $routes = [];

	/**
	 * Not found route.
	 *
	 * @var string|null
	 */
	private $notFoundRoute;

	/**
	 * @inheritdoc
	 */
	public function mapNotFoundRoute(string $route):void {
		if (!$this->hasRoute($route)) {
			throw new RouterException("The route \"$route\" does not exist and can not "
				."be used as the not found route", $this);
		}
		$this->notFoundRoute = $route;
	}

	/**
	 * Maps a sub router.
	 *
	 * @param string $route
	 * @param RouterInterface $router
	 * @throws ExistingRouteException
	 */
	public function mapRouter(string $route, RouterInterface $router):void {
		$this->addRoute($route, $router);
	}

	/**
	 * Maps an object.
	 *
	 * @param string $route
	 * @param RoutableInterface $object
	 * @throws ExistingRouteException
	 */
	public function mapObject(string $route, RoutableInterface $object):void {
		$this->addRoute($route, $object);
	}

	/**
	 * Maps a class. The class must implement the interface RoutableInterface.
	 *
	 * @param string $route
	 * @param string $class
	 * @throws ExistingRouteException
	 * @throws RouterException
	 */
	public function mapClass(string $route, string $class):void {
		if (!is_subclass_of($class, RoutableInterface::class)) {
			throw new RouterException("The class \"$class\" does not implement "
				."\"".RoutableInterface::class."\"", $this);
		}
		$this->addRoute($route, $class);
	}

	/**
	 * @param string $route
	 * @param callable $callable
	 * @throws ExistingRouteException
	 */
	public function mapCallable(string $route, callable $callable) {
		$this->addRoute($route, $callable);
	}

	/**
	 * Verifies if a route exists.
	 *
	 * @param string $route
	 * @return bool
	 */
	public function hasRoute(string $route):bool {
		return $this->getRouteTarget($route, false) !== false;
	}

	/**
	 * @param string $route
	 * @param RouterInterface||string|callable $target
	 * @throws ExistingRouteException
	 */
	private function addRoute(string $route, $target):void {
		if (isset($this->routes[$route])) {
			throw new ExistingRouteException($route, $this);
		}
		$this->routes[$route] = $target;
	}

	/**
	 * Returns the target of a given route or false if no target is found.
	 *
	 * @param string $route
	 * @param bool $allowNotFound (default: true)
	 * @return bool|mixed
	 */
	private function getRouteTarget(string $route, bool $allowNotFound = null) {
		// if there is a direct match, returning the target
		if (array_key_exists($route, $this->routes)) {
			return $this->routes[$route];
		}

		// search for a registered route matching the requested route using fnmatch()
		foreach ($this->routes as $registeredRoute => $target) {
			if (fnmatch($registeredRoute, $route)) {
				return $target;
			}
		}

		// using the not found route
		if ($allowNotFound !== false && $this->notFoundRoute) {
			return $this->notFoundRoute;
		}

		return false;
	}

	/**
	 * @inheritdoc
	 * @param string $route
	 * @param bool|null $allowNotFound
	 * @throws RouterException
	 */
	public function processRoute(string $route, bool $allowNotFound = null):void {
		// checking the route
		if (($target = $this->getRouteTarget($route, $allowNotFound)) === false) {
			throw new RouterException("The route \"$route\" does not exist");
		}

		// is an object
		if ($target instanceof RoutableInterface) {
			$response = $target->process(new Request($this));
			if (!$response->isSent()) {
				$response->send();
			}
		}
		
		// is a router
		else if ($target instanceof RouterInterface) {
			$target->processRoute($route);
		}
		
		// is callable
		else if (is_callable($target)) {
			$response = $target(new Request($this));
			if ($response instanceof ResponseInterface && !$response->isSent()) {
				$response->send();
			}
		}

		// is a class
		else if (is_string($target)) {
			/** @var RoutableInterface $object */
			$object = new $target($this);
			$response = $object->process(new Request($this));
			if (!$response->isSent()) {
				$response->send();
			}
		}
	}
}