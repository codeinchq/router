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
namespace CodeInc\Router;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Class DynamicRouter
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class DynamicRouter extends AbstractRouter
{
    /**
     * @var string
     */
    private $controllersNamespace;

    /**
     * @var string
     */
    private $uriPrefix;

    /**
     * @var ControllerInstantiatorInterface
     */
    private $controllerInstantiator;

    /**
     * DynamicRouter constructor.
     *
     * @param string $controllersNamespace
     * @param string $uriPrefix
     * @param ControllerInstantiatorInterface $controllerInstantiator
     * @throws RouterException
     */
    public function __construct(string $controllersNamespace, string $uriPrefix,
        ControllerInstantiatorInterface $controllerInstantiator)
    {
        if (empty($uriPrefix)) {
            throw RouterException::emptyUriPrefix();
        }
        if (empty($controllersNamespace)) {
            throw RouterException::emptyControllersNamespace();
        }
        $this->controllersNamespace = $controllersNamespace;
        $this->uriPrefix = $uriPrefix;
        $this->controllerInstantiator = $controllerInstantiator;
    }

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @return ControllerInterface|null
     * @throws RouterException
     */
    protected function getController(ServerRequestInterface $request):?ControllerInterface
    {
        $requestRoute = $request->getUri()->getPath();
        if (substr($requestRoute, 0, strlen($this->uriPrefix)) == $this->uriPrefix) {
            $controllerClass = $this->controllersNamespace
                .str_replace('/', '\\', substr($requestRoute, strlen($this->uriPrefix)));
            if (class_exists($controllerClass)) {
                if (is_subclass_of($controllerClass, ControllerInterface::class)) {
                    throw RouterException::notAController($controllerClass);
                }
                return $this->controllerInstantiator->instantiate($request, $controllerClass);
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
    public function getControllerUri(string $controllerClass):string
    {
        if (!is_subclass_of($controllerClass, ControllerInterface::class)) {
            throw RouterException::notAController($controllerClass);
        }
        if (!substr($controllerClass, 0, strlen($this->controllersNamespace)) == $controllerClass) {
            throw RouterException::notWithinNamespace($controllerClass, $this->controllersNamespace);
        }
        return $this->uriPrefix
            .str_replace('\\', '/', substr($controllerClass, strlen($this->controllersNamespace)));
    }
}