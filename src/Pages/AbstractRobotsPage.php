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
// Date:     28/11/2017
// Time:     13:13
// Project:  lib-gui
//
namespace CodeInc\GUI\Pages;
use CodeInc\GUI\Pages\Exceptions\PageHTTPHeadersSentException;
use CodeInc\GUI\Pages\Exceptions\PageRenderingException;


/**
 * Class AbstractRobotsPage
 *
 * @package CodeInc\GUI\Pages\Models
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractRobotsPage extends AbstractPage {
	/**
	 * @var array
	 */
	protected $siteMaps = [];

	/**
	 * @var array
	 */
	protected $rules = [];

	/**
	 * @var string
	 */
	protected $charset = 'utf-8';

	/**
	 * @param string $siteMap
	 * @return bool
	 */
	protected function addSiteMap(string $siteMap):bool {
		if (!in_array($siteMap, $this->siteMaps)) {
			$this->siteMaps[] = $siteMap;
			return true;
		}
		return false;
	}

	/**
	 * @param string $allow
	 * @param string|null $userAgent
	 */
	protected function addAllowRule(string $allow, string $userAgent = null) {
		$this->addRule('Allow', $allow, $userAgent);
	}

	/**
	 * @param string $allow
	 * @param string|null $userAgent
	 */
	protected function addDisallowRule(string $allow, string $userAgent = null) {
		$this->addRule('Disallow', $allow, $userAgent);
	}

	/**
	 * @param string $type
	 * @param string $value
	 * @param string|null $userAgent
	 */
	protected function addRule(string $type, string $value, string $userAgent = null) {
		$this->rules[$userAgent ?? '*'][$type][] = $value;
	}

	/**
	 * @throws PageRenderingException
	 */
	public function render() {
		try {
			if (headers_sent()) {
				throw new PageHTTPHeadersSentException($this);
			}
			header("Content-Type: text/plain; charset=$this->charset");
			foreach ($this->rules as $userAgent => $rules) {
				echo "User-agent: $userAgent\n";
				foreach ($rules as $type => $value) {
					echo "$type: $value\n";
				}
			}
			foreach ($this->siteMaps as $siteMap) {
				echo "Sitemap: ".$siteMap."\n";
			}
		}
		catch (\Exception $exception) {
			throw new PageRenderingException($this, null, $exception);
		}
	}
}