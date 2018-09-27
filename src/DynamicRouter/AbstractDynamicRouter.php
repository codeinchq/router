<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2018 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material is strictly forbidden unless prior    |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     24/09/2018
// Project:  Router
//
declare(strict_types=1);
namespace CodeInc\Router\DynamicRouter;
use CodeInc\Router\Controllers\ControllerInterface;
use CodeInc\Router\RouterException;
use CodeInc\Router\RouterInterface;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Class AbstractDynamicRouter
 *
 * @package CodeInc\Router\DynamicRouter
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractDynamicRouter implements RouterInterface
{
    /**
     * Returns the router's URI prefix.
     *
     * @return string
     */
    abstract public function getUriPrefix():string;

    /**
     * Returns the controllers' base namespace.
     *
     * @return string
     */
    abstract public function getControllersNamespace():string;

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @return null|string
     * @throws RouterException
     */
    public function getControllerClass(ServerRequestInterface $request):?string
    {
        $requestRoute = $request->getUri()->getPath();
        $uriPrefix = $this->getUriPrefix();
        if (substr($requestRoute, 0, strlen($uriPrefix)) == $uriPrefix) {
            $controllerClass = $this->getControllersNamespace()
                .str_replace('/', '\\', substr($requestRoute, strlen($uriPrefix)));
            if (class_exists($controllerClass)) {
                if (is_subclass_of($controllerClass, ControllerInterface::class)) {
                    throw RouterException::notAController($controllerClass);
                }
                return $controllerClass;
            }
        }
        return null;
    }

    /**
     * @inheritdoc
     * @param string $controllerClass
     * @return string
     * @throws RouterException
     */
    public function getUri(string $controllerClass):string
    {
        $controllersNamespace = $this->getControllersNamespace();
        if (!is_subclass_of($controllerClass, ControllerInterface::class)) {
            throw RouterException::notAController($controllerClass);
        }
        if (!substr($controllerClass, 0, strlen($controllersNamespace)) == $controllerClass) {
            throw RouterException::notWithinNamespace($controllerClass, $controllersNamespace);
        }
        return $this->getUriPrefix()
            .str_replace('\\', '/', substr($controllerClass, strlen($controllersNamespace)));
    }
}