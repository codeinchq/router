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
// Date:     09/10/2018
// Project:  Router
//
declare(strict_types=1);
namespace CodeInc\Router\Instantiator;
use CodeInc\CollectionInterface\CountableCollectionInterface;
use CodeInc\Router\Exceptions\NotAnInstantiatorException;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class HandlerInstantiatorAggregator
 *
 * @package CodeInc\Router\Instantiator
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class HandlerInstantiatorAggregator implements HandlerInstantiatorInterface, CountableCollectionInterface
{
    /**
     * @var HandlerInstantiatorInterface[]
     */
    private $instantiators = [];

    /**
     * @var int
     */
    private $iteratorPosition = 0;

    /**
     * HandlerInstantiatorAggregator constructor.
     *
     * @param iterable|null $instantiators
     */
    public function __construct(?iterable $instantiators = null)
    {
        if ($instantiators !== null) {
            $this->addInstantiators($instantiators);
        }
    }

    /**
     * Adds an instantiator.
     *
     * @param HandlerInstantiatorInterface $instantiator
     */
    public function addInstantiator(HandlerInstantiatorInterface $instantiator):void
    {
        $this->instantiators[] = $instantiator;
    }

    /**
     * Adds multiple instantiators.
     *
     * @param iterable $instantiators
     */
    public function addInstantiators(iterable $instantiators):void
    {
        foreach ($instantiators as $instantiator) {
            if (!$instantiator instanceof HandlerInstantiatorInterface) {
                throw new NotAnInstantiatorException($instantiator);
            }
            $this->addInstantiator($instantiator);
        }
    }

    /**
     * Instantiates a request handler or returns NULL if the request handler can not be instantiated.
     *
     * @param string $handlerClass
     * @return RequestHandlerInterface|null
     */
    public function instantiate(string $handlerClass):?RequestHandlerInterface
    {
        foreach ($this->instantiators as $instantiator) {
            if (($handler = $instantiator->instantiate($handlerClass)) !== null) {
                return $handler;
            }
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function rewind():void
    {
        $this->iteratorPosition = 0;
    }

    /**
     * @inheritdoc
     */
    public function next():void
    {
        $this->iteratorPosition++;
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function valid():bool
    {
        return array_key_exists($this->iteratorPosition, $this->instantiators);
    }

    /**
     * @inheritdoc
     * @return HandlerInstantiatorInterface
     */
    public function current():HandlerInstantiatorInterface
    {
        return $this->instantiators[$this->iteratorPosition];
    }

    /**
     * @inheritdoc
     * @return int
     */
    public function key():int
    {
        return $this->iteratorPosition;
    }

    /**
     * @inheritdoc
     * @return int
     */
    public function count():int
    {
        return count($this->instantiators);
    }
}