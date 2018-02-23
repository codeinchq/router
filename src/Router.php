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
declare(strict_types=1);
namespace CodeInc\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


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
	 * @var string[]|RequestHandlerInterface[]
	 */
	private $routes = [];

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
	 * @throws RouterException
	 */
	public function setNotFoundRoute(string $route):void
	{
		if (!isset($this->routes[$route])) {
			throw new RouterException(
				sprintf("The route %s does not exist", $route),
				$this
			);
		}
		$this->notFoundRoute = $route;
	}

	/**
	 * Adds a route.
	 *
	 * @param string $route Route (can be a pattern compatible with fnmatch())
	 * @param string|RequestHandlerInterface $target Callable, class or object implementing RoutableInterface
	 * @throws RouterException
	 */
	public function addRoute(string $route, $target)
	{
		if (!is_subclass_of($target, RequestHandlerInterface::class)) {
			throw new RouterException(
				sprintf("The target for the route \"%s\" must be either a class name or an object implementing %s",
					$route, RequestHandlerInterface::class),
				$this
			);
		}
		if (isset($this->routes[$route])) {
			throw new RouterException(
				sprintf("The route \"%s\" already exist", $route),
				$this
			);
		}
		$this->routes[$route] = $target;
	}

	/**
	 * @inheritdoc
	 * @see RouterInterface::hasHandler()
	 * @param ServerRequestInterface $request
	 * @return bool
	 */
	public function canHandle(ServerRequestInterface $request):bool
	{
		return $this->getRequestRoute($request) !== null;
	}

	/**
	 * Returns the route corresponding to a request or null if no route is found.
	 *
	 * @param ServerRequestInterface $request
	 * @param bool|null $allowNotFound
	 * @return null|string
	 */
	protected function getRequestRoute(ServerRequestInterface $request, bool $allowNotFound = null):?string {
		$route = $request->getUri()->getPath();

		// if there is direct match within the registered routes
		if (isset($this->routes[$route])) {
			return $route;
		}

		// searching within the registered using fnmatch()
		else {
			foreach ($this->routes as $registeredRoute => $target) {
				if (fnmatch($registeredRoute, $route)) {
					return $target;
				}
			}
		}

		// if no route is found, using the notfound route
		if ($allowNotFound !== false && $this->notFoundRoute) {
			return $route;
		}

		return null;
	}

	/**
	 * @inheritdoc
	 * @see RouterInterface::getRequestHandler()
	 * @param ServerRequestInterface $request
	 * @param bool|null $allowNotFound
	 * @return RequestHandlerInterface
	 * @throws RouterException
	 */
	public function getRequestHandler(ServerRequestInterface $request,
		bool $allowNotFound = null):RequestHandlerInterface
	{
		// getting the handler
		if (($route = $this->getRequestRoute($request, $allowNotFound)) === null) {
			throw new RouterException(
				sprintf("No route found for the request \"%s\"", $request->getUri()),
				$this
			);
		}
		$handler = $this->routes[$route];

		// if the handler is a class name, instantiating
		if (!$handler instanceof RequestHandlerInterface) {
			try {
				$handler = new $handler($this);
			}
			catch (\Throwable $exception) {
				throw new RouterException(
					sprintf("Error while instantiating the request handler %s for the request \"%s\"",
						(is_object($handler) ? get_class($handler) : (string)$handler), $request->getUri()),
					$this, null, $exception
				);
			}
		}

		return $handler;
	}

	/**
	 * @inheritdoc
	 * @see RequestHandlerInterface::handle()
	 * @return ResponseInterface
	 * @throws RouterException
	 */
	public function handle(ServerRequestInterface $request):ResponseInterface
	{
		return $this->process($request, $this->getRequestHandler($request));
	}

	/**
	 * @inheritdoc
	 * @see MiddlewareInterface::process()
	 * @return ResponseInterface
	 * @throws RouterException
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface
	{
		try {
			return $this->getRequestHandler($request)->handle($request);
		}
		catch (\Throwable $exception) {
			throw new RouterException(
				sprintf("Erorr while processing the request to \"%s\"", $request->getUri()),
				$this, null, $exception
			);
		}
	}
}