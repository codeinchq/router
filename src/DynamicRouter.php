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
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class DynamicRouter
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class DynamicRouter extends AbstractDynamicRouter
{
    /**
     * @var string
     */
    private $requestHandlersNamespace;

    /**
     * @var string
     */
    private $uriPrefix;

    /**
     * @var RequestHandlerFactoryInterface
     */
    private $requestHandlerFactory;

    /**
     * DynamicRouter constructor.
     *
     * @param string $requestHandlersNamespace
     * @param string $uriPrefix
     * @param RequestHandlerFactoryInterface|null $requestHandlerFactory
     * @throws RouterException
     */
    public function __construct(string $requestHandlersNamespace, string $uriPrefix,
        ?RequestHandlerFactoryInterface $requestHandlerFactory = null)
    {
        if (empty($uriPrefix)) {
            throw RouterException::emptyUriPrefix();
        }
        if (empty($requestHandlersNamespace)) {
            throw RouterException::emptyRequestHandlersNamespace();
        }
        $this->requestHandlersNamespace = $requestHandlersNamespace;
        $this->uriPrefix = $uriPrefix;
        $this->requestHandlerFactory = $requestHandlerFactory ?? new RequestHandlerFactory();
    }

    /**
     * Returns the router's URI prefix.
     *
     * @return string
     */
    public function getUriPrefix():string
    {
        return $this->uriPrefix;
    }

    /**
     * Returns the requests handler's base namespace.
     *
     * @return string
     */
    public function getRequestHandlersNamespace():string
    {
        return $this->requestHandlersNamespace;
    }

    /**
     * @inheritdoc
     * @param string $handlerClass
     * @return RequestHandlerInterface
     * @throws RouterException
     */
    protected function instantiateHandler(string $handlerClass):RequestHandlerInterface
    {
        return $this->requestHandlerFactory->factory($handlerClass);
    }
}