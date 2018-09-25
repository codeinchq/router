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
use CodeInc\Router\RequestHandlerInstantiator\RequestHandlerInstantiatorInterface;
use CodeInc\Router\RouterException;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class DynamicRouter
 *
 * @package CodeInc\Router\DynamicRouter
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class DynamicRouter extends AbstractDynamicRouter
{
    /**
     * @var RequestHandlerInstantiatorInterface
     */
    private $requestHandlersInstantiator;

    /**
     * DynamicRouter constructor.
     *
     * @param string $requestHandlersNamespace
     * @param string $uriPrefix
     * @param RequestHandlerInstantiatorInterface $requestHandlersInstantiator
     * @throws RouterException
     */
    public function __construct(string $requestHandlersNamespace, string $uriPrefix,
        RequestHandlerInstantiatorInterface $requestHandlersInstantiator)
    {
        parent::__construct($requestHandlersNamespace, $uriPrefix);
        $this->requestHandlersInstantiator = $requestHandlersInstantiator;
    }

    /**
     * @inheritdoc
     * @param string $handlerClass
     * @return RequestHandlerInterface
     */
    protected function instantiate(string $handlerClass):RequestHandlerInterface
    {
        return $this->requestHandlersInstantiator->instantiate($handlerClass);
    }
}