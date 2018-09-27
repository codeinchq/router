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
// Date:     05/03/2018
// Time:     11:53
// Project:  Router
//
declare(strict_types = 1);
namespace CodeInc\Router\Resolvers;
use CodeInc\Router\Resolvers\ResolverInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class AbstractStaticResolver
 *
 * @package CodeInc\Router\Resolvers
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractStaticResolver implements ResolverInterface
{
    /**
     * Returns all the requests handler and their associated routes.
     *
     * @return iterable|string[]
     */
    abstract public function getControllers():iterable;

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @return null|string
     */
    public function getControllerClass(ServerRequestInterface $request):?string
    {
        $requestRoute = $request->getUri()->getPath();
        foreach ($this->getControllers() as $route => $controllerClass) {
            if (fnmatch($route, $requestRoute, FNM_CASEFOLD)) {
                return $controllerClass;
            }
        }
        return null;
    }

    /**
     * @inheritdoc
     * @param RequestHandlerInterface|string $requestHandler
     * @return null|string
     */
    public function getUri(string $controllerClass):?string
    {
        foreach ($this->getControllers() as $uri => $knownControllerClass) {
            if ($controllerClass == $knownControllerClass) {
                return $uri;
            }
        }
        return null;
    }
}