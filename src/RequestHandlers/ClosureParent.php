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
// Date:     23/02/2018
// Time:     15:16
// Project:  lib-psr15router
//
declare(strict_types = 1);
namespace CodeInc\Router\RequestHandlers;
use Psr\Http\Message\RequestInterface;


/**
 * Class ClosureParent
 *
 * @package CodeInc\Router\RequestHandlers
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ClosureParent {
	/**
	 * @var RequestInterface
	 */
	private $request;

	/**
	 * ClosureParent constructor.
	 *
	 * @param RequestInterface $request
	 */
	public function __construct(RequestInterface $request)
	{
		$this->request = $request;
	}

	/**
	 * @return RequestInterface
	 */
	public function getRequest():RequestInterface
	{
		return $this->request;
	}
}