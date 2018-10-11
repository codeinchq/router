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
// Date:     11/10/2018
// Project:  Router
//
declare(strict_types=1);
namespace CodeInc\Router\Instantiator;
use CodeInc\Router\Exceptions\NotAHandlerException;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class CallableHandlerInstantiator
 *
 * @package CodeInc\Router\Instantiator
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class CallableHandlerInstantiator implements HandlerInstantiatorInterface
{
    /**
     * @var callable
     */
    private $instantiator;

    /**
     * CallableHandlerInstantiator constructor.
     *
     * @param callable $instantiator
     */
    public function __construct(callable $instantiator)
    {
        $this->instantiator = $instantiator;
    }

    /**
     * Instantiates a request handler or returns NULL if the request handler can not be instantiated.
     *
     * @param string $handlerClass
     * @return RequestHandlerInterface|null
     * @throws NotAHandlerException
     */
    public function instantiate(string $handlerClass):?RequestHandlerInterface
    {
        $handler = call_user_func($this->instantiator, $handlerClass);
        if ($handler !== null && !$handler instanceof RequestHandlerInterface) {
            throw new NotAHandlerException($handler);
        }
        return $handler;
    }
}