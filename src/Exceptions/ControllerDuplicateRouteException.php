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
use CodeInc\Router\RouterInterface;
use Throwable;


/**
 * Class ControllerDuplicateRouteException
 *
 * @package CodeInc\Router\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ControllerDuplicateRouteException extends \LogicException implements RouterException
{
    /**
     * @var string
     */
    private $route;

    /**
     * @var string
     */
    private $controllerClass;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * ControllerDuplicateRouteException constructor.
     *
     * @param string $route
     * @param string $controllerClass
     * @param RouterInterface $router
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $route, string $controllerClass, RouterInterface $router,
        int $code = 0, Throwable $previous = null)
    {
        $this->route = $route;
        $this->controllerClass = $controllerClass;
        $this->router = $router;
        parent::__construct(
            sprintf("A controller already exists for the route '%s'.", $route),
            $code,
            $previous
        );
    }

    /**
     * @return string
     */
    public function getRoute():string
    {
        return $this->route;
    }

    /**
     * @return string
     */
    public function getControllerClass():string
    {
        return $this->controllerClass;
    }

    /**
     * @return RouterInterface
     */
    public function getRouter():RouterInterface
    {
        return $this->router;
    }
}