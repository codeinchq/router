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
use CodeInc\Router\Exceptions\ControllerHandlingException;
use CodeInc\Router\Exceptions\DuplicateRouteException;
use CodeInc\Router\Exceptions\NotAControllerException;
use CodeInc\Router\Interfaces\ControllerValidatorInterface;
use CodeInc\Router\Interfaces\ControllerInstantiatorInterface;
use CodeInc\Router\Interfaces\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Class Router
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class Router implements RouterInterface {
	/**
	 * @var string[]
	 */
	private $routes = [];

	/**
	 * @var string
	 */
	private $notFoundControllerClass;

	/**
	 * @var ControllerInstantiatorInterface
	 */
	private $instantiator;

	/**
	 * @var ControllerValidatorInterface
	 */
	private $validator;

	/**
	 * Router constructor.
	 *
	 * @param ControllerInstantiatorInterface|null $instantiator
	 * @param ControllerValidatorInterface|null $validator
	 */
	public function __construct(?ControllerInstantiatorInterface $instantiator = null,
		?ControllerValidatorInterface $validator = null)
	{
		$this->setInstantiator($instantiator
			?? new DefaultControllerInstantiator());
		$this->setValidator($validator
			?? new DefaultControllerValidator());
	}

	/**
	 * @param ControllerInstantiatorInterface $instantiator
	 */
	public function setInstantiator(ControllerInstantiatorInterface $instantiator):void
	{
		$this->instantiator = $instantiator;
	}

	/**
	 * @param ControllerValidatorInterface $validator
	 */
	public function setValidator(ControllerValidatorInterface $validator):void
	{
		$this->validator = $validator;
	}

	/**
	 * Sets the not found controller class.
	 *
	 * @param string $notFoundControllerClass
	 * @throws NotAControllerException
	 */
	public function setNotFoundController(string $notFoundControllerClass):void
	{
		if (!$this->validator->validate($notFoundControllerClass)) {
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
		if (!$this->validator->validate($controllerClass)) {
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
		if ($controllerClass = $this->getControllerClass($request)) {
			try {
				$controller = $this->instantiator->instanciateController($controllerClass);
				$controller->injectRequest($request);
				return $controller->getResponse();
			}
			catch (\Throwable $exception) {
				throw new ControllerHandlingException($controllerClass, $this, null, $exception);
			}
		}
		return new NotFoundResponse();
	}
}