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
// Date:     25/09/2018
// Project:  Router
//
declare(strict_types=1);
namespace CodeInc\Router;


/**
 * Class RouterAggregator
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RouterAggregator extends AbstractRouterAggregator
{
    /**
     * @var RouterInterface[]
     */
    private $routersRegistry = [];

    /**
     * @var \Traversable|null
     */
    private $routersIterator;

    /**
     * RouterAggregator constructor.
     *
     * @param iterable|null $routers
     * @throws RouterException
     */
    public function __construct(?iterable $routers = null)
    {
        if ($routers instanceof \Traversable) {
            $this->routersIterator = $routers;
        }
        elseif ($routers !== null) {
            foreach ($routers as $router) {
                if (!$router instanceof RouterInterface) {
                    throw RouterException::notARouter($router);
                }
                $this->addRouter($router);
            }
        }
    }

    /**
     * Adds a router.
     *
     * @param RouterInterface $router
     */
    public function addRouter(RouterInterface $router):void
    {
        $this->routersRegistry[] = $router;
    }

    /**
     * Returns the routers.
     *
     * @return \Generator|RouterInterface[]
     * @throws RouterException
     */
    protected function getRouters():iterable
    {
        if ($this->routersIterator) {
            foreach ($this->routersIterator as $router) {
                if (!$router instanceof RouterInterface) {
                    throw RouterException::notARouter($router);
                }
                yield $router;
            }
        }
        foreach ($this->routersRegistry as $router) {
            yield $router;
        }
    }
}