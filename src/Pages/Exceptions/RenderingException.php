<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE - CONFIDENTIAL                                |
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
// Date:     04/12/2017
// Time:     17:27
// Project:  lib-codeinclib
//
namespace CodeInc\GUI\Pages\Exceptions;
use CodeInc\GUI\Pages\Interfaces\PageInterface;


/**
 * Class RenderingException
 *
 * @package CodeInc\GUI\Pages\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RenderingException extends PageException {
	/**
	 * RenderingException constructor.
	 *
	 * @param PageInterface $parentPage
	 * @param int $code
	 * @param \Throwable|null $previous
	 */
	public function __construct(PageInterface $parentPage, int $code = null, \Throwable $previous = null) {
		parent::__construct($parentPage,"Error while render the ".get_class($parentPage)." page", null, $previous);
	}
}