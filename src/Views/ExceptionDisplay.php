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
use CodeInc\GUI\Views\Interfaces\ViewInterface;


/**
 * Class ExceptionDisplay
 *
 * @package CodeInc\GUI\Views
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ExceptionDisplay implements ViewInterface {
	/**
	 * @var \Exception
	 */
	private $exception;

	/**
	 * ExceptionDisplay constructor.
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
		<div class="exception parent" data-type="<?=htmlspecialchars(get_class($this->exception))?>">
			<div class="exception-title">Error</div>
			<?
			// Renders the main exception
			$this->renderException($this->exception, "Main");

			// Renders the previous exceptions
			$i = 0;
			if ($previous = $this->exception->getPrevious()) { ?>
				<? do { ?>
					<? $this->renderException($previous, "Previous #".++$i) ?>
				<? } while ($previous = $previous->getPrevious()); ?>
			<? }

			// Renders the CSS
			$this->renderStyles();
			?>
		</div>
		<?
	}

	/**
	 * Renders the Exception
	 *
	 * @param \Exception $exception
	 * @param string $position
	 */
	private function renderException(\Exception $exception, string $position) {
		?>
		<div class="exception" data-pos="<?=htmlspecialchars($position)?>" data-type="<?=htmlspecialchars(get_class($exception))?>">
			<div class="exception-header">
				<span class="exception-pos"><?=htmlspecialchars("[$position]")?></span>
				<span class="exception-class"><?=htmlspecialchars(get_class($exception))?></span>
			</div>
			<div class="exception-message">
				<?=htmlspecialchars($exception->getMessage())?>
			</div>
			<div class="exception-location">
				<?=htmlspecialchars($exception->getFile()).':'.$exception->getLine()?>
			</div>
			<div class="exception-trace">
				<?=nl2br(htmlspecialchars($exception->getTraceAsString()))?>
			</div>
		</div>
		<?
	}

	/**
	 * Renders the CSS styles.
	 */
	private function renderStyles() {
		?>
		<style scoped>
			div.exception.parent {
				border: 2px solid red;
				border-bottom-width: 5px;
				margin: 20px;
				color: #000;
				background: #fff;
				font-family: Arial, sans-serif;
				font-size: 14px;
				list-style: decimal;
			}
			div.exception.parent > div.exception-title {
				background: red;
				color: #fff;
				padding: 3px;
				font-weight: bold;
				text-transform: uppercase;
			}
			div.exception.parent > div.exception {
				margin: 10px;
			}
			div.exception.parent > div.exception:not(:last-of-type) {
				border-bottom: 1px dotted red;
				padding-bottom: 10px;
			}
			div.exception.parent > div.exception div.exception-header {
				font-weight: bold;
				font-size: .8em;
				margin-bottom: 5px;
				color: red;
			}
			div.exception.parent > div.exception div.exception-header span.exception-pos {
				text-transform: uppercase;
			}
			div.exception.parent > div.exception div.exception-location, div.exception.parent > div.exception div.exception-trace {
				font-size: .8em;
				opacity: .8;
				color: #555;
				margin-top: 5px;
			}
		</style>
		<?
	}
}