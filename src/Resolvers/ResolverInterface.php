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
namespace CodeInc\Router\Resolvers;

/**
 * Interface ResolverInterface
 *
 * @package CodeInc\Router\Resolvers
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface ResolverInterface
{
    /**
     * Returns the controller's in charge or handling a given route or NULL if none is available.
     *
     * @param string $route
     * @return string|null
     */
    public function getControllerClass(string $route):?string;

    /**
     * Returns the route to a controller or NULL if the route can not be computed.
     *
     * @param string $controllerClass
     * @return string|null
     */
    public function getControllerRoute(string $controllerClass):?string;
}