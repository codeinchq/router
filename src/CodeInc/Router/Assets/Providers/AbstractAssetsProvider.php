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
// Date:     20/02/2018
// Time:     13:46
// Project:  lib-router
//
declare(strict_types=1);
namespace CodeInc\Router\Assets\Providers;


/**
 * Class AbstractProvider
 *
 * @package CodeInc\Router\Assets\Providers
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractAssetsProvider implements AssetsProviderInterface {
	/**
	 * Version number.
	 *
	 * @var string|null
	 */
	private $versionNumber;

	/**
	 * AbstractProvider constructor.
	 *
	 * @param null|string $versionNumber
	 */
	public function __construct(?string $versionNumber = null) {
		$this->setVersionNumber($versionNumber);
	}

	/**
	 * @param null|string $versionNumber
	 */
	public function setVersionNumber(?string $versionNumber):void {
		$this->versionNumber = $versionNumber;
	}

	/**
	 * @return null|string
	 */
	public function getVersionNumber():?string {
		return $this->versionNumber;
	}

	/**
	 * Adds the version number to the url if set
	 *
	 * @param string $url
	 * @return string
	 */
	protected function addVersionNumber(string $url):string {
		if ($this->versionNumber) {
			return (strchr($url, "?") ? "&" : "?")."v$this->versionNumber";
		}
		return $url;
	}
}