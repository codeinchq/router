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
// Date:     02/03/2018
// Time:     09:52
// Project:  lib-router
//
declare(strict_types = 1);
namespace CodeInc\Router;
use CodeInc\Router\Controller\ControllerInterface;
use CodeInc\Router\Exception\ControllerProcessingException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class Router
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class Router extends AbstractRouter implements RequestHandlerInterface {
	/**
	 * @inheritdoc
	 * @param string $controllerClass
	 * @param ServerRequestInterface $request
	 * @return ResponseInterface
	 * @throws ControllerProcessingException
	 */
	protected function processController(string $controllerClass, ServerRequestInterface $request):ResponseInterface
	{
		try {
			/** @var ControllerInterface $controller */
			$controller = new $controllerClass($request);
			return $controller->process();
		}
		catch (\Throwable $exception) {
			throw new ControllerProcessingException($controllerClass, $this, null, $exception);
		}
	}
}