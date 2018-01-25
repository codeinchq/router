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
// Date:     25/01/2018
// Time:     12:50
// Project:  lib-gui
//
namespace CodeInc\GUI\Pages\Exceptions;
use Throwable;


/**
 * Class PagesManagerUnknownUriException
 *
 * @package CodeInc\GUI\Pages\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class PagesManagerUnknownUriException extends PagesManagerException {
	/**
	 * PagesManagerUnknownUriException constructor.
	 *
	 * @param int|null $code
	 * @param Throwable|null $previous
	 */
	public function __construct(int $code = null, Throwable $previous = null) {
		parent::__construct("The current page's URL can not be found in the \$_SERVER array, unable to render the current page",
			$code ?? 0, $previous);
	}
}