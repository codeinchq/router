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
// Time:     14:42
// Project:  Router
//
declare(strict_types = 1);
namespace CodeInc\Router\Instantiators;
use CodeInc\Router\ControllerInterface;
use CodeInc\Router\Exceptions\NotAControllerException;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Interface InstantiatorInterface
 *
 * @package CodeInc\Router\Instantiators
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface InstantiatorInterface
{
    /**
     * Returns a controller instance.
     *
     * @param string $controllerClass
     * @param ServerRequestInterface $serverRequest
     * @return ControllerInterface
     * @throws NotAControllerException
     */
    public function instantiate(string $controllerClass, ServerRequestInterface $serverRequest):ControllerInterface;
}