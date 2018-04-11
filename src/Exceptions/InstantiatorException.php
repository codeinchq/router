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
// Time:     14:44
// Project:  Router
//
declare(strict_types = 1);
namespace CodeInc\Router\Exceptions;
use CodeInc\Router\Instantiators\InstantiatorInterface;
use Throwable;


/**
 * Class InstantiatorException
 *
 * @package CodeInc\Router\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class InstantiatorException extends RouterException
{
    /**
     * @var InstantiatorInterface|null
     */
    private $instantiator;

    /**
     * InstantiatorException constructor.
     *
     * @param string $message
     * @param InstantiatorInterface|null $instantiator
     * @param int|null $code
     * @param null|Throwable $previous
     */
    public function __construct(string $message,
        ?InstantiatorInterface $instantiator = null,
        ?int $code = null, ?Throwable $previous = null)
    {
        $this->instantiator = $instantiator;
        parent::__construct($message, null, $code, $previous);
    }

    /**
     * @return InstantiatorInterface|null
     */
    public function getInstantiator():?InstantiatorInterface
    {
        return $this->instantiator;
    }
}