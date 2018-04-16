<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material  is strictly forbidden unless prior   |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     11/04/2018
// Time:     19:21
// Project:  Router
//
declare(strict_types=1);
namespace CodeInc\Router\Controllers;
use CodeInc\Router\Instantiators\DefaultInstantiator;
use CodeInc\Router\Instantiators\InstantiatorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class ControllerRequestHandler to transform a controller into a PSR-15 RequestHandler
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ControllerRequestHandler implements RequestHandlerInterface
{
    /**
     * @var string
     */
    private $controllerClass;

    /**
     * @var DefaultInstantiator|InstantiatorInterface
     */
    private $instantiator;

    /**
     * ControllerRequestHandler constructor.
     *
     * @param string $controllerClass
     * @param InstantiatorInterface|null $instantiator
     */
    public function __construct(string $controllerClass, InstantiatorInterface $instantiator = null)
    {
        $this->controllerClass = $controllerClass;
        $this->instantiator = $instantiator ?? new DefaultInstantiator();
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request):ResponseInterface
    {
        return $this->instantiator->instantiate($this->controllerClass, $request)->process();
    }
}