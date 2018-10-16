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
// Date:     16/10/2018
// Project:  Router
//
declare(strict_types=1);
namespace CodeInc\Router\Resolvers\Annotation;
use CodeInc\DirectoryClassesIterator\RecursiveDirectoryClassesIterator;
use CodeInc\Router\Resolvers\StaticHandlerResolver;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;


/**
 * Class AnnotationResolver
 *
 * @package CodeInc\Router\Resolvers
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class AnnotationResolver extends StaticHandlerResolver
{
    /**
     * @var string
     */
    private $routePrefix;

    /**
     * @var Reader
     */
    private $annotationReader;

    /**
     * AnnotationResolver constructor.
     *
     * @param string $routePrefix
     * @param Reader|null $annotationReader
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct(string $routePrefix = '', ?Reader $annotationReader = null)
    {
        parent::__construct();
        $this->routePrefix = $routePrefix;
        $this->annotationReader = $annotationReader ?? new AnnotationReader();
    }

    /**
     * Adds all the handler in a directory having the annotation @Routable.
     *
     * @param string $dirPath
     */
    public function addDirectory(string $dirPath):void
    {
        foreach (new RecursiveDirectoryClassesIterator($dirPath) as $class) {
            /** @var Routable $annotation */
            if ($annotation = $this->annotationReader->getClassAnnotation($class, Routable::class)) {
                $this->addRoute($this->routePrefix.$annotation->route, $class->getName());
                if ($annotation->altRoutes) {
                    foreach ($annotation->altRoutes as $route) {
                        $this->addRoute($this->routePrefix.$route, $class->getName());
                    }
                }
            }
        }
    }
}