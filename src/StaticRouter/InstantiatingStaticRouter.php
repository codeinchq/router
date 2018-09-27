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
// Date:     27/09/2018
// Project:  Router
//
declare(strict_types=1);
namespace CodeInc\Router\StaticRouter;
use CodeInc\Router\Controllers\ControllerInstantiatorInterface;
use CodeInc\Router\Controllers\ControllerInterface;
use CodeInc\Router\InstantiatingRouterInterface;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Class InstantiatingStaticRouter
 *
 * @package CodeInc\Router\StaticRouter
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class InstantiatingStaticRouter extends StaticRouter implements InstantiatingRouterInterface
{
    /**
     * @var ControllerInstantiatorInterface
     */
    private $controllerInstantiator;

    /**
     * InstantiatingStaticRouter constructor.
     *
     * @param ControllerInstantiatorInterface $controllerInstantiator
     */
    public function __construct(ControllerInstantiatorInterface $controllerInstantiator)
    {
        $this->controllerInstantiator = $controllerInstantiator;
    }

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @return null|ControllerInterface
     */
    public function getController(ServerRequestInterface $request):?ControllerInterface
    {
        if ($controllerClass = $this->getControllerClass($request)) {
            return $this->controllerInstantiator->instantiate($controllerClass, $request);
        }
        return null;
    }
}