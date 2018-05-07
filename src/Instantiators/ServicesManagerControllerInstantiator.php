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
// Date:     13/03/2018
// Time:     14:43
// Project:  Router
//
declare(strict_types = 1);
namespace CodeInc\Router\Instantiators;
use CodeInc\Router\Controllers\ControllerInterface;
use CodeInc\Router\Exceptions\NotAControllerException;
use CodeInc\ServicesManager\ServicesManager;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Class ServicesManagerControllerInstantiator
 *
 * @link https://github.com/CodeIncHQ/ServicesManager
 * @link https://packagist.org/packages/codeinc/services-manager
 * @see ServicesManager
 * @package CodeInc\Router\Instantiators
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ServicesManagerControllerInstantiator implements ControllerInstantiatorInterface
{
    /**
     * @var ServicesManager
     */
    private $serviceManager;

    /**
     * ServiceManagerInstantiator constructor.
     *
     * @param ServicesManager $serviceManager
     */
    public function __construct(ServicesManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @inheritdoc
     * @throws NotAControllerException
     * @throws \CodeInc\ServicesManager\Exceptions\ClassNotFoundException
     * @throws \CodeInc\ServicesManager\Exceptions\NewInstanceException
     */
    public function instantiate(string $controllerClass, ServerRequestInterface $request):ControllerInterface
    {
        $controller = $this->serviceManager->instantiate($controllerClass, [$request]);
        if (!$controller instanceof ControllerInterface) {
            throw new NotAControllerException($controllerClass);
        }
        return $controller;
    }
}