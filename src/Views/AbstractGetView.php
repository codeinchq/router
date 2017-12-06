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
// Time:     15:53
// Project:  lib-codeinclib
//
namespace CodeInc\GUI\Views;


/**
 * Class AbstractView
 *
 * @package CodeInc\GUI\Views
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractGetView implements ViewInterface, ViewGetInterface {
	/**
	 * Returns the views generated code.
	 *
	 * @throws ViewException
	 */
	public function get():string {
		ob_start();
		$this->render();
		return ob_get_clean();
	}

	/**
	 * Returns the view.
	 *
	 * @return string
	 */
	public function __toString():string {
		try {
			return $this->get();
		} catch (\Exception $exception) {
			return "Error: ".$exception->getMessage();
		}
	}
}