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
// Project:  Router
//
declare(strict_types = 1);
namespace CodeInc\Router;
use CodeInc\Router\Controllers\ControllerInterface;
use CodeInc\Router\Exceptions\ControllerHandlingException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class AbstractRouter
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractRouter implements RouterInterface
{
    /**
     * Processes a controller
     *
     * @param ServerRequestInterface $request
     * @return null|ControllerInterface
     */
    abstract protected function getController(ServerRequestInterface $request):?ControllerInterface;

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @throws ControllerHandlingException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface
    {
        try {
            // if a controller is available for the given request
            if (($controller = $this->getController($request)) !== null) {
                // instantiating the controller & processing the request
                return $controller->process();
            }
        }
        catch (\Throwable $exception) {
            throw new ControllerHandlingException(
                (isset($controller) && is_object($controller)) ? get_class($controller) : null,
                $this,
                0,
                $exception
            );
        }

        // else passing the request the provided handler
        return $handler->handle($request);
    }
}