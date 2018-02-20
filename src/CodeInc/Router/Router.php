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
use CodeInc\Router\Interfaces\RoutedClassInterface;
use CodeInc\Router\Interfaces\RoutedObjectInterface;
use CodeInc\Router\Request\Request;
use CodeInc\Router\Response\ResponseInterface;


/**
 * Class Router
 *
 * @package CodeInc\GUI\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class Router implements RouterInterface {
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
	 * @inheritdoc
	 * @return array
	 */
	public function getRoutes():array {
		return array_keys($this->routes);
	}

	/**
	 * @param RouterInterface $router
	 * @throws ExistingRouteException
	 */
	public function mapRouter(RouterInterface $router):void {
		foreach ($router->getRoutes() as $route) {
			$this->addRoute($route, $router);
		}
	}

	/**
	 * @param RoutedObjectInterface $object
	 * @throws ExistingRouteException
	 */
	public function mapRoutedObject(RoutedObjectInterface $object):void {
		$this->addRoute($object->getRoute(), $object);
	}

	/**
	 * @param string $route
	 * @param RoutableInterface $object
	 * @throws ExistingRouteException
	 */
	public function mapObject(string $route, RoutableInterface $object):void {
		$this->addRoute($route, $object);
	}

	/**
	 * @param string $class
	 * @throws ExistingRouteException
	 * @throws RouterException
	 */
	public function mapRoutedClass(string $class):void {
		if (!is_subclass_of($class, RoutedClassInterface::class)) {
			throw new RouterException("The class \"$class\" does not implement "
				."\"".RoutedClassInterface::class."\"", $this);
		}
		/** @var RoutedClassInterface $class */
		$this->addRoute($class::getRoute(), $class);
	}

	/**
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
	 * @param string $route
	 * @param object|string|callable $target
	 * @throws ExistingRouteException
	 */
	private function addRoute(string $route, $target):void {
		if (isset($this->routes[$route])) {
			throw new ExistingRouteException($route, $this);
		}
		$this->routes[$route] = $target;
	}

	/**
	 * @inheritdoc
	 * @param string $route
	 * @return bool
	 */
	public function hasRoute(string $route):bool {
		return array_key_exists($route, $this->routes);
	}

	/**
	 * @inheritdoc
	 * @param string $route
	 * @return ResponseInterface
	 * @throws RouterException
	 */
	public function processRoute(string $route) {
		// checking the route
		if (!$this->hasRoute($route)) {
			if (!$this->notFoundRoute) {
				throw new RouterException("The route \"$route\" does not exist");
			}
			$route = $this->notFoundRoute;
		}

		// preparing
		$request = new Request($this);
		$target = $this->routes[$route];

		// is an object
		if ($target instanceof RoutableInterface) {
			return $target->process($request);
		}
		
		// is a router
		elseif ($target instanceof RouterInterface) {
			return $target->processRoute($route);
		}
		
		// is callable
		elseif (is_callable($target)) {
			$response = $target($request);
			if ($response instanceof ResponseInterface) {
				throw new RouterException("The response of the callable for the route \"$route\" is not "
					."a vaid response (all responses must implement \"".ResponseInterface::class."\")",
					$this);
			}
			return $response;
		}

		// is a class
		else {
			/** @var RoutableInterface $object */
			$object = new $target($this);
			return $object->process($request);
		}
	}

	private function processObject(RoutableInterface $object, Request $request) {

	}
}