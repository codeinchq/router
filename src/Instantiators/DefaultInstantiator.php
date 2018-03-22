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
// Project:  lib-router
//
declare(strict_types = 1);
namespace CodeInc\Router\Instantiators;
use CodeInc\Router\ControllerInterface;
use CodeInc\Router\Exceptions\DefaultInstantiatorException;
use CodeInc\Router\Exceptions\RouterException;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Class DefaultInstantiator
 *
 * @package CodeInc\Router\Instantiators
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class DefaultInstantiator implements InstantiatorInterface
{
    /**
     * @inheritdoc
     */
    public function instantiate(string $controllerClass,
        ServerRequestInterface $request):ControllerInterface
    {
        $refClass = new \ReflectionClass($controllerClass);
        return $refClass->newInstanceArgs($this->getConstructorArgs($refClass, $request));
    }

    /**
     * @param \ReflectionClass $class
     * @param ServerRequestInterface $request
     * @return array
     * @throws DefaultInstantiatorException
     */
    private function getConstructorArgs(\ReflectionClass $class,
        ServerRequestInterface $request):array
    {
        if ($class->isSubclassOf(DefaultInstantiatorControllerInterface::class)) {
            return [$request];
        }
        else if ($class->hasMethod("__construct")) {
            $construct = $class->getMethod("__construct");
            $args = [];
            foreach ($construct->getParameters() as $i => $parameter) {
                if (!$parameter->getType()->isBuiltin()
                    && ($parameter->getClass()->isSubclassOf(ServerRequestInterface::class) ||
                        $parameter->getClass()->getName() == ServerRequestInterface::class)) {
                    $args[] = $request;
                }
                else if ($parameter->isDefaultValueAvailable()) {
                    $args[] = $parameter->getDefaultValue();
                }
                else {
                    throw new DefaultInstantiatorException(
                        sprintf("The parameter \$%s (#%s) of %s::__construct() "
                            ."is not of type %s and does not have a default value, "
                            ."unable to instantiate the controller %s",
                            $parameter->getName(), $i + 1, $class->getName(),
                            ServerRequestInterface::class, $class->getName())
                    );
                }
            }
            return $args;
        }
        else {
            return [];
        }
    }
}