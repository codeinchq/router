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
// Date:     06/12/2017
// Time:     18:54
// Project:  lib-gui
//
namespace CodeInc\GUI\Views;


/**
 * Class ExceptionRender
 *
 * @package CodeInc\GUI\Views
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ExceptionRender implements ViewInterface {
	/**
	 * @var \Exception
	 */
	private $exception;

	/**
	 * ExceptionRenderComponent constructor.
	 *
	 * @param \Exception $exception
	 */
	public function __construct(\Exception $exception) {
		$this->exception = $exception;
	}

	/**
	 * Renders the HTML code.
	 */
	public function render() {
		?>
		<div class="exception" data-class="<?=htmlspecialchars(get_class($this->exception))?>">
			<?
			// Renders the current exception
			$this->renderException($this->exception);

			// Renders the previous exceptions
			if ($previous = $this->exception->getPrevious()) { ?>
				<ul class="previous-exceptions">
					<? do { ?>
						<li><? $this->renderException($previous) ?></li>
					<? } while ($previous = $previous->getPrevious()); ?>
				</ul>
			<? }

			// Renders the CSS Styles
			$this->renderStyles();
			?>
		</div>
		<?
	}

	/**
	 * Renders hte Exception
	 *
	 * @param \Exception $exception
	 */
	private function renderException(\Exception $exception) {
		?>
		<div class="exception-message">
			<strong><?=htmlspecialchars(get_class($exception))?>&nbsp;:</strong> <?=htmlspecialchars($exception->getMessage())?>
		</div>
		<div class="exception-location">
			<?=htmlspecialchars($exception->getFile()).':'.$exception->getLine()?>
		</div>
		<div class="exception-trace">
			<?=nl2br(htmlspecialchars($exception->getTraceAsString()))?>
		</div>
		<?
	}

	/**
	 * Renders the CSS styles.
	 */
	private function renderStyles() {
		?>
		<style scoped>
			div.exception {
				background: orangered;
				padding: 10px;
				margin: 20px;
				color: white;
				font-family: Arial, sans-serif;
				font-size: 14px;
			}
			div.exception-location, div.exception-trace {
				font-size: .8em;
				opacity: .8;
			}
			div.exception-trace {
				margin-top: 10px;
			}
			div.exception ul.previous-exceptions {
				padding: 0;
				margin: 15px 0 0 20px;
			}
			div.exception ul.previous-exceptions li:not(:first-of-type) {
				margin-top: 15px;
			}
		</style>
		<?
	}
}