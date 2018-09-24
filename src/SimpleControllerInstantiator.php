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
// Date:     13/03/2018
// Time:     14:43
// Project:  Router
//
declare(strict_types = 1);
namespace CodeInc\Router;
use CodeInc\Router\Interfaces\ControllerInstantiatorInterface;
use CodeInc\Router\Interfaces\ControllerInterface;


/**
 * Class SimpleControllerInstantiator
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class SimpleControllerInstantiator implements ControllerInstantiatorInterface
{
    /**
     * @inheritdoc
     * @throws RouterException
     */
    public function instantiate(string $controllerClass):ControllerInterface
    {
        $controller = new $controllerClass();
        if (!$controller instanceof ControllerInterface) {
            throw RouterException::notAController($controllerClass);
        }
        return $controller;
    }
}