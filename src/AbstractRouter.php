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
// Date:     05/03/2018
// Time:     11:53
// Project:  lib-router
//
declare(strict_types = 1);
namespace CodeInc\Router;
use CodeInc\Psr7Responses\NotFoundResponse;
use CodeInc\Router\Exceptions\ControllerProcessingException;
use CodeInc\Router\Exceptions\DuplicateRouteException;
use CodeInc\Router\Exceptions\NotAControllerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Class AbstractRouter
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractRouter implements RouterInterface {
	/**
	 * @var string[]
	 */
	private $routes = [];

	/**
	 * @var string
	 */
	private $notFoundControllerClass;

	/**
	 * Sets the not found controller class.
	 *
	 * @param string $notFoundControllerClass
	 * @throws NotAControllerException
	 */
	public function setNotFoundController(string $notFoundControllerClass):void
	{
		if (!is_subclass_of($notFoundControllerClass, ControllerInterface::class)) {
			throw new NotAControllerException($notFoundControllerClass, $this);
		}
		$this->notFoundControllerClass = $notFoundControllerClass;
	}

	/**
	 * Adds a route to a controller.
	 *
	 * @param string $route
	 * @param string $controllerClass
	 * @throws DuplicateRouteException
	 * @throws NotAControllerException
	 */
	public function addRoute(string $route, string $controllerClass):void
	{
		if (isset($this->routes[$route])) {
			throw new DuplicateRouteException($route, $this);
		}
		if (!is_subclass_of($controllerClass, ControllerInterface::class)) {
			throw new NotAControllerException($controllerClass, $this);
		}
		$this->routes[$route] = $controllerClass;
	}

	/**
	 * @inheritdoc
	 */
	public function canHandle(ServerRequestInterface $request):bool
	{
		return $this->getControllerClass($request) !== null;
	}

	/**
	 * Processes a controller
	 *
	 * @param ServerRequestInterface $request
	 * @return null|string
	 */
	protected function getControllerClass(ServerRequestInterface $request):?string
	{
		$requestRoute = $request->getUri()->getPath();

		// if there is a direct route matching the request
		if (isset($this->routes[$requestRoute])) {
			return $this->routes[$requestRoute];
		}

		// if there is a pattern route matching the request
		foreach ($this->routes as $route => $controller) {
			if (fnmatch($route, $requestRoute)) {
				return $controller;
			}
		}

		// not found controller
		if ($this->notFoundControllerClass) {
			return $this->notFoundControllerClass;
		}

		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function handle(ServerRequestInterface $request):ResponseInterface
	{
		if ($controller = $this->getControllerClass($request)) {
			return $this->processController($controller, $request);
		}
		return new NotFoundResponse();
	}

	/**
	 * Processes the controller and returns the PSR-7 response.
	 *
	 * @param string|ControllerInterface $controllerClass
	 * @param ServerRequestInterface $request
	 * @return ResponseInterface
	 * @throws ControllerProcessingException
	 */
	abstract protected function processController(string $controllerClass,
		ServerRequestInterface $request):ResponseInterface;
}