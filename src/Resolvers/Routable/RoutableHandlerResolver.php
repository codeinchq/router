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
// Date:     12/10/2018
// Project:  Router
//
declare(strict_types=1);
namespace CodeInc\Router\Resolvers\Routable;
use CodeInc\DirectoryClassesIterator\RecursiveDirectoryClassesIterator;
use CodeInc\Router\Exceptions\NotARoutableHandlerException;
use CodeInc\Router\Resolvers\StaticHandlerResolver;


/**
 * Class RoutableHandlerResolver
 *
 * @package CodeInc\Router\Resolvers\Routable
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RoutableHandlerResolver extends StaticHandlerResolver
{
    /**
     * RoutableHandlerResolver constructor.
     *
     * @param iterable|null $handlerClasses
     */
    public function __construct(?iterable $handlerClasses = null)
    {
        parent::__construct();
        if ($handlerClasses) {
            $this->addHandlers($handlerClasses);
        }
    }

    /**
     * Adds multiple routable handlers to the resolver.
     *
     * @param iterable $handlerClasses
     */
    public function addHandlers(iterable $handlerClasses):void
    {
        foreach ($handlerClasses as $handlersClass) {
            $this->addHandler($handlersClass);
        }
    }

    /**
     * Adds a routable handler to the resolver.
     *
     * @param string $handlerClass
     * @throws NotARoutableHandlerException
     */
    public function addHandler(string $handlerClass):void
    {
        $isHandlerRoutable = false;
        if (is_subclass_of($handlerClass, RoutableRequestHandlerInterface::class)) {
            /** @var RoutableRequestHandlerInterface $handlerClass */
            /** @noinspection PhpStrictTypeCheckingInspection */
            $this->addRoute($handlerClass::getRoute(), $handlerClass);
            $isHandlerRoutable = true;
        }
        if (is_subclass_of($handlerClass, MultiRoutableRequestHandlerInterface::class)) {
            /** @var MultiRoutableRequestHandlerInterface $handlerClass */
            foreach ($handlerClass::getRoutes() as $route) {
                /** @noinspection PhpStrictTypeCheckingInspection */
                $this->addRoute($route, $handlerClass);
            }
            $isHandlerRoutable = true;
        }
        if (!$isHandlerRoutable) {
            throw new NotARoutableHandlerException($handlerClass);
        }
    }

    /**
     * Adds all the handler in a directory implementing RoutableRequestHandlerInterface
     * or MultiRoutableRequestHandlerInterface
     *
     * @param string $dirPath
     */
    public function addDirectory(string $dirPath):void
    {
        foreach (new RecursiveDirectoryClassesIterator($dirPath) as $class)
        {
            if ($class->isSubclassOf(RoutableRequestHandlerInterface::class)
                || $class->isSubclassOf(MultiRoutableRequestHandlerInterface::class)) {
                $this->addHandler($class->getName());
            }
        }
    }
}