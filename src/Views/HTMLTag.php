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
// Time:     15:40
// Project:  codeinc.fr
//
namespace CodeInc\GUI\Views;
use CodeInc\ArrayAccess\ArrayAccessTrait;
use CodeInc\GUI\Views\Interfaces\ReturnableViewInterface;
use CodeInc\GUI\Views\Interfaces\StringifiableViewInterface;
use CodeInc\GUI\Views\Interfaces\ViewInterface;


/**
 * Class HTMLTag
 *
 * @package CodeInc\GUI\Views\HTMLTag
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class HTMLTag implements \ArrayAccess, ViewInterface, StringifiableViewInterface, ReturnableViewInterface {
	use ArrayAccessTrait;

	/**
	 * Tag attributes.
	 *
	 * @var array
	 */
	private $attributes = [];

	/**
	 * Tag name.
	 *
	 * @var string
	 */
	private $tagName;

	/**
	 * HTMLTag constructor.
	 *
	 * @param string $tagName
	 * @param array|null $attributes
	 * @throws ViewException
	 */
	public function __construct(string $tagName, array $attributes = null) {
		$this->setTagName($tagName);
		if ($attributes !== null) {
			$this->setAttributes($attributes);
		}
	}

	/**
	 * Sets the tag name.
	 *
	 * @param string $tagName
	 * @throws ViewException
	 */
	private function setTagName(string $tagName) {
		if (!preg_match('/^[a-z]+$/ui', $tagName)) {
			throw new ViewException("The tag name \"$tagName\" is invalid");
		}
		$this->tagName = $tagName;
	}

	/**
	 * Returns the tag's name.
	 *
	 * @return string
	 */
	public function getTagName():string {
		return $this->tagName;
	}

	/**
	 * @param array $attributes
	 */
	public function setAttributes(array $attributes) {
		$this->attributes = $attributes;
	}

	/**
	 * @param string $name
	 * @param null $value
	 */
	public function setAttribute(string $name, $value = null) {
		$this->attributes[$name] = (string)$value;
	}

	/**
	 * Returns the tag's attributes.
	 *
	 * @return array
	 */
	public function getAttributes():array {
		return $this->attributes;
	}

	/**
	 * Returns the tag's opening HTML source.
	 *
	 * @return string
	 */
	public function get():string {
		$tag = "<$this->tagName";
		foreach ($this->attributes as $name => $value) {
			$tag .= " $name";
			if (!empty($value)) {
				$tag .= "=\"".htmlspecialchars($value)."\"";
			}
		}
		return "$tag>";
	}

	/**
	 * Alias of get()
	 *
	 * @see HTMLTag::get()
	 * @return string
	 */
	public function __toString():string {
		return $this->get();
	}

	/**
	 * Renders the tag's opening HTML source.
	 */
	public function render() {
		echo $this->get();
	}

	/**
	 * Returns the tags closure.
	 *
	 * @return string
	 */
	public function getClosure():string {
		return "</$this->tagName>";
	}

	/**
	 * Renders the tags closure.
	 */
	public function renderClosure() {
		echo $this->getClosure();
	}

	/**
	 * Returns a pointer to the array accessible via ArrayAccess.
	 *
	 * @return array
	 */
	protected function &getAccessibleArray():array {
		return $this->attributes;
	}
}