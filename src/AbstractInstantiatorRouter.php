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
// Date:     05/03/2018
// Time:     11:53
// Project:  Router
//
declare(strict_types = 1);
namespace CodeInc\Router;
use CodeInc\Router\Controllers\ControllerInterface;
use CodeInc\Router\Instantiators\DefaultInstantiator;
use CodeInc\Router\Instantiators\InstantiatorInterface;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Class AbstractInstantiatorRouter
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractInstantiatorRouter extends AbstractRouter
{
    /**
     * @var InstantiatorInterface
     */
    private $instantiator;

    /**
     * @var string
     */
    private $notFoundControllerClass;

    /**
     * Router constructor.
     *
     * @param InstantiatorInterface|null $instantiator
     */
    public function __construct(?InstantiatorInterface $instantiator = null)
    {
        $this->instantiator = $instantiator;
    }

    /**
     * Returns the controller class for a given request or null if no controller is available.
     *
     * @param ServerRequestInterface $request
     * @return null|string
     */
    abstract protected function getControllerClass(ServerRequestInterface $request):?string;

    /**
     * Returns the instantiator.
     *
     * @return InstantiatorInterface
     */
    private function getInstantiator():InstantiatorInterface
    {
        if (!$this->instantiator instanceof InstantiatorInterface) {
            $this->instantiator = new DefaultInstantiator();
        }
        return $this->instantiator;
    }

    /**
     * Sets the not found controller class.
     *
     * @param string $notFoundControllerClass
     */
    public function setNotFoundController(string $notFoundControllerClass):void
    {
        $this->notFoundControllerClass = $notFoundControllerClass;
    }

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @return ControllerInterface|null
     * @throws Exceptions\NotAControllerException
     */
    protected function getController(ServerRequestInterface $request):?ControllerInterface
    {
        if ($controllerClass = $this->getControllerClass($request)) {
            return $this->getInstantiator()->instantiate($controllerClass, $request);
        }
        if ($this->notFoundControllerClass !== null) {
            return $this->getInstantiator()->instantiate($this->notFoundControllerClass, $request);
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function canProcess(ServerRequestInterface $request):bool
    {
        return $this->getControllerClass($request) !== null;
    }
}