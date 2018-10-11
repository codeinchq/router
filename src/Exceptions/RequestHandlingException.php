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
// Date:     28/09/2018
// Project:  Router
//
declare(strict_types=1);
namespace CodeInc\Router\Exceptions;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class RequestHandlingException
 *
 * @package CodeInc\Router\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
final class RequestHandlingException extends \RuntimeException implements RouterException
{
    /**
     * @var RequestHandlerInterface
     */
    private $handler;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * RequestHandlingException constructor.
     *
     * @param RequestHandlerInterface $handler
     * @param ServerRequestInterface $request
     * @param int $code
     * @param null|\Throwable $previous
     */
    public function __construct(RequestHandlerInterface $handler, ServerRequestInterface $request,
        int $code = 0, ?\Throwable $previous = null)
    {
        $this->handler = $handler;
        $this->request = $request;
        parent::__construct(
            sprintf("Error while processing a request with the handler '%s'", get_class($handler)),
            $code,
            $previous
        );
    }

    /**
     * @return ServerRequestInterface
     */
    public function getRequest():ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * @return RequestHandlerInterface
     */
    public function getHandler():RequestHandlerInterface
    {
        return $this->handler;
    }
}