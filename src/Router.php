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
use CodeInc\Router\Exceptions;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;


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
	 * @var string[]|RoutableInterface[]
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
	 * @throws Exceptions\InexistantNotFoundRouteException
	 */
	public function setNotFoundRoute(string $route):void
	{
		if (!isset($this->routes[$route])) {
			throw new Exceptions\InexistantNotFoundRouteException($route, $this);
		}
		$this->notFoundRoute = $route;
	}

	/**
	 * Adds a route.
	 *
	 * @param string $route Route (can be a pattern compatible with fnmatch())
	 * @param string|RoutableInterface|callable $target Callable, class or object implementing RoutableInterface
	 * @throws Exceptions\InvalidTargetException
	 * @throws Exceptions\ExistingRouteException
	 */
	public function addRoute(string $route, $target)
	{
		if (!is_callable($target) || !is_subclass_of($target, RoutableInterface::class)) {
			throw new Exceptions\InvalidTargetException($target, $this);
		}
		if (isset($this->routes[$route])) {
			throw new Exceptions\ExistingRouteException($route, $this);
		}
		$this->routes[$route] = $target;
	}

	/**
	 * @inheritdoc
	 * @param resource $request
	 * @return bool
	 */
	public function canProcessRequest(RequestInterface $request):bool
	{
		return $this->getRequestTarget($request) !== null;
	}

	/**
	 * Returns the route for a given request.
	 *
	 * @param RequestInterface $request
	 * @param bool|null $allowNotFound
	 * @return string|null
	 */
	protected function getRequestTarget(RequestInterface $request, bool $allowNotFound = null):?string
	{
		$route = $request->getUri()->getPath();

		// if there is direct match within the registered routes
		if (isset($this->routes[$route])) {
			return $this->routes[$route];
		}

		// searching within the registered using fnmatch()
		foreach ($this->routes as $registeredRoute => $target) {
			if (fnmatch($registeredRoute, $route)) {
				return $target;
			}
		}

		// if no route is found, using the notfound route
		if ($allowNotFound !== false && $this->notFoundRoute) {
			return $this->routes[$this->notFoundRoute];
		}

		return null;
	}

	/**
	 * @inheritdoc
	 * @param RequestInterface $request
	 * @param bool|null $allowNotFound
	 * @return ResponseInterface
	 * @throws Exceptions\RouteNotFoundException
	 * @throws Exceptions\UnknownTargetTypeException
	 */
	public function processRequest(RequestInterface $request, bool $allowNotFound = null):ResponseInterface
	{
		// getting the target
		if (($target = $this->getRequestTarget($request, $allowNotFound)) === null) {
			throw new Exceptions\RouteNotFoundException($request, $this);
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
		else {
			throw new Exceptions\UnknownTargetTypeException($target, $this);
		}
	}

	/**
	 * Processes a class.
	 *
	 * @param string $class
	 * @param RequestInterface $request
	 * @return ResponseInterface
	 * @throws Exceptions\RequestProcessingException
	 */
	public function processClass(string $class, RequestInterface $request):ResponseInterface
	{
		try {
			/** @var RoutableInterface $object */
			$object = new $class($this);
			return $object->processRequest($request);
		}
		catch (\Throwable $exception) {
			throw new Exceptions\RequestProcessingException(
				$request, $this,
				"Error while processing the request \"{$request->getUri()}\" using the class \"$class\"",
				$exception
			);
		}
	}

	/**
	 * Processes the object.
	 *
	 * @param RoutableInterface $object
	 * @param RequestInterface $request
	 * @return ResponseInterface
	 * @throws Exceptions\RequestProcessingException
	 */
	private function processObject(RoutableInterface $object, RequestInterface $request):ResponseInterface
	{
		try {
			return $object->processRequest($request);
		}
		catch (\Throwable $exception) {
			throw new Exceptions\RequestProcessingException(
				$request, $this,
				"Error while processing the request \"{$request->getUri()}\" using the object "
					."\"".get_class($object)."\"",
				$exception
			);
		}
	}

	/**
	 * Processses a callable target.
	 *
	 * @param callable $callable
	 * @param RequestInterface $request
	 * @return mixed
	 * @throws Exceptions\RequestProcessingException
	 */
	private function processCallable(callable $callable, RequestInterface $request):ResponseInterface
	{
		try {
			$response = $callable($request);
			if ($response instanceof ResponseInterface) {
				throw new Exceptions\CallableInvalidResponseException($this);
			}
			return $response;
		}
		catch (\Throwable $exception) {
			throw new Exceptions\RequestProcessingException(
				$request, $this,
				"Error while processing the request \"{$request->getUri()}\" using a callable",
				$exception
			);
		}
	}

	/**
	 * @inheritdoc
	 * @param RequestInterface $request
	 * @param ResponseInterface $response
	 * @throws Exceptions\ResponseSentException
	 */
	public function sendResponse(ResponseInterface $response, ?RequestInterface $request = null):void
	{
		if (headers_sent()) {
			throw new Exceptions\ResponseSentException($response, $this);
		}

		// making the response compatible with the request
		if ($request && $response->getProtocolVersion() != $response->getProtocolVersion()) {
			$response = $response->withProtocolVersion($request->getProtocolVersion());
		}

		// sending the headers
		header("HTTP/{$response->getProtocolVersion()} {$response->getStatusCode()} {$response->getReasonPhrase()}", true);
		foreach ($response->getHeaders() as $header => $values) {
			header("$header: ".implode(", ", $values));
		}

		// sending the body
		$body = $response->getBody();
		if (!$response->hasHeader("Content-Length") && ($size = $body->getSize()) !== null) {
			header("Content-Length: $size");
		}
		while (!$body->eof()) {
			if ($line = \GuzzleHttp\Psr7\readline($body, 1024)) {
				echo $line;
				flush();
			}
		}
	}
}