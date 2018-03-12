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
// Date:     12/03/2018
// Time:     18:19
// Project:  lib-router
//
declare(strict_types = 1);
namespace CodeInc\Router\Instantiators;
use CodeInc\Router\ControllerInterface;
use CodeInc\Router\Exceptions\NotAControllerException;
use CodeInc\ServiceManager\ServiceInterface;
use CodeInc\ServiceManager\ServiceManager;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Class ServiceManagerInstantiator
 *
 * @package CodeInc\Router\Instantiators
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ServiceManagerInstantiator implements InstantiatorInterface, ServiceInterface
{
    /**
     * @var ServiceManager
     */
    private $serviceManager;

    /**
     * ServiceManagerInstantiator constructor.
     *
     * @param ServiceManager $serviceManager
     */
    public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @inheritdoc
     */
    public function instanciate(string $controllerClass,
        ServerRequestInterface $request):ControllerInterface
    {
        if (!$this->serviceManager->hasInstance(ServerRequestInterface::class)) {
            $this->serviceManager->addInstance($request);
        }
        $controller = $this->serviceManager->getInstance($controllerClass);
        if (!$controller instanceof ControllerInterface) {
            throw new NotAControllerException($controllerClass);
        }
        return $controller;
    }
}