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
// Time:     13:16
// Project:  lib-router
//
namespace CodeInc\Router\Pages;
use CodeInc\Router\Pages\Exceptions\PageException;
use CodeInc\Router\Responses\SimpleContentResponse;
use CodeInc\Router\Responses\ResponseInterface;


/**
 * Class AbstractSiteMapPage
 *
 * @package CodeInc\GUI\Pages
 * @author Joan Fabrégat <joan@codeinc.fr>
 * @deprecated
 */
abstract class AbstractSiteMapPage extends AbstractPage {
	const CHANGE_FREQ_DAILY = 'daily';
	const CHANGE_FREQ_WEEKLY = 'weekly';
	const CHANGE_FREQ_MONTHLY = 'monthly';

	/**
	 * @var string
	 */
	protected $charset = "utf-8";

	/**
	 * @var array
	 */
	protected $pages = [];

	/**
	 * @param string $URI
	 * @param float $priority
	 * @param int $lastModTimestamp
	 * @param string|null $changeFreq
	 */
	protected function addPage(string $URI, float $priority, int $lastModTimestamp, string $changeFreq) {
		$this->pages[$URI] = [
			'priority' => $priority,
			'lastModTimestamp' => $lastModTimestamp,
			'changeFreq' => $changeFreq,
		];
	}

	/**
	 * @param string $pageURI
	 * @param string $lang
	 * @param string $alternateURI
	 * @throws PageException
	 */
	protected function addPageAlternate(string $pageURI, string $lang, string $alternateURI) {
		if (!array_key_exists($pageURI, $this->pages)) {
			throw new PageException("The page \"$pageURI\" is not registered, unable to add an alternate", $this);
		}
		$this->pages[$pageURI]['alternates'][$lang] = $alternateURI;
	}

	/**
	 * @return ResponseInterface
	 */
	public function process():ResponseInterface {
		$response = new SimpleContentResponse($this);
		$response->setHttpHeader("Content-Type", "application/xml; charset=$this->charset");
		$response->addContent('<?xml version="1.0" encoding="UTF-8"?>'."\n"
			.'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">'."\n");

		foreach ($this->pages as $URI => $infos) {
			$response->addContent("\t<url>\n"
				."\t\t<loc>".htmlspecialchars($URI)."</loc>\n"
				."\t\t<lastmod>".date('Y-m-d', $infos['lastModTimestamp'])."</lastmod>\n"
				."\t\t<changefreq>".$infos['changeFreq']."</changefreq>\n"
				."\t\t<priority>".$infos['priority']."</priority>\n");
			if (is_array($infos['alternates']) && $infos['alternates']) {
				foreach ($infos['alternates'] as $lang => $URI) {
					$response->addContent("\t\t".'<xhtml:link rel="alternate" hreflang="'.$lang.'" href="'.htmlspecialchars($URI).'" />'."\n");
				}
			}
			$response->addContent("\t</url>\n");
		}
		$response->addContent("</urlset>\n");
		return $response;
	}
}