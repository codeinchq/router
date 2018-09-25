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
// Date:     25/09/2018
// Project:  Router
//
declare(strict_types=1);
namespace CodeInc\Router;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Interface RouterInterface
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface RouterInterface
{
    /**
     * Returns the request handler to handle the given HTTP request or NULL if no handler is available.
     *
     * @param ServerRequestInterface $request
     * @return null|RequestHandlerInterface
     */
    public function getHandler(ServerRequestInterface $request):?RequestHandlerInterface;

    /**
     * Returns the URI of a request handler or NULL if the handler's URI can not be computed.
     *
     * @param string|RequestHandlerInterface $requestHandler A request handler or a request handler's class
     * @return string|null
     */
    public function getUri($requestHandler):?string;
}