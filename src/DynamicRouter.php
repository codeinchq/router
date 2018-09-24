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
use CodeInc\Router\Interfaces\ControllerInterface;
use CodeInc\Router\Interfaces\RouterInterface;
use CodeInc\Router\Interfaces\ControllerInstantiatorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class DynamicRouter
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class DynamicRouter implements RouterInterface
{
    /**
     * @var string
     */
    private $controllersBaseNamespace;

    /**
     * @var string
     */
    private $baseUri;

    /**
     * @var ControllerInstantiatorInterface
     */
    private $controllerInstantiator;

    /**
     * DynamicRouter constructor.
     *
     * @param string $controllersBaseNamespace
     * @param ControllerInstantiatorInterface $controllerInstantiator
     * @param string $baseUri
     * @throws RouterException
     */
    public function __construct(string $controllersBaseNamespace,
        ControllerInstantiatorInterface $controllerInstantiator, string $baseUri = '/')
    {
        if (empty($baseUri)) {
            throw RouterException::emptyBaseUri();
        }
        $this->controllersBaseNamespace = $controllersBaseNamespace;
        $this->controllerInstantiator = $controllerInstantiator;
        $this->baseUri = $baseUri;
    }

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws RouterException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface
    {
        $requestUri = $request->getUri()->getPath();
        if (substr($requestUri, 0, strlen($this->baseUri)) == $this->baseUri) {
            $controllerClass = $this->controllersBaseNamespace
                .str_replace('/', '\\', substr($requestUri, strlen($this->baseUri)));
            if (class_exists($controllerClass)) {
                if (is_subclass_of($controllerClass, ControllerInterface::class)) {
                    throw RouterException::notAController($controllerClass);
                }
                $handler = $this->controllerInstantiator->instantiate($controllerClass);
            }
        }

        return $handler->handle($request);
    }

    /**
     * Returns the URI for a given controller within the current namespace.
     *
     * @param string|ControllerInterface $controller
     * @return string
     * @throws RouterException
     */
    public function getControllerUri($controller):string
    {
        if ($controller instanceof ControllerInterface) {
            $controller = get_class($controller);
        }
        if (!is_subclass_of($controller, ControllerInterface::class)) {
            throw RouterException::notAController($controller);
        }
        if (!substr($controller, 0, strlen($this->controllersBaseNamespace)) == $this->controllersBaseNamespace) {
            throw RouterException::notWithinNamespace($controller, $this->controllersBaseNamespace);
        }
        return $this->baseUri
            .str_replace('\\', '/', substr($controller, strlen($this->controllersBaseNamespace)));
    }
}