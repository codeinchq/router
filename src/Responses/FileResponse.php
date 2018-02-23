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
// Time:     19:15
// Project:  lib-router
//
declare(strict_types = 1);
namespace CodeInc\Router\Responses;
use GuzzleHttp\Psr7\Response;
use function GuzzleHttp\Psr7\stream_for;


/**
 * Class FileResponse
 *
 * @package CodeInc\Router\Responses
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class FileResponse extends Response {
	/**
	 * FileResponse constructor.
	 *
	 * @param string $filePath
	 * @param null|string $fileName
	 * @param null|string $mimeType
	 * @param bool|null $asAttachment
	 * @param int $status
	 * @param array $headers
	 * @param string $version
	 * @param null|string $reason
	 * @throws ResponseException
	 */
	public function __construct(string $filePath, ?string $fileName = null, ?string $mimeType = null,
		?bool $asAttachment = null, int $status = 200, array $headers = [],
		string $version = '1.1', ?string $reason = null)
	{
		if (!is_file($filePath)) {
			throw new ResponseException(
				sprintf("The path \"%s\" is not a file or does not exist", $filePath),
				$this
			);
		}

		$headers["Content-Type"] = $mimeType ?? mime_content_type($filePath);
		$headers["Content-Disposition"] = sprintf("%s; filename=\"%s\"",
			$asAttachment !== false ? "attachment" : "inline",
			$fileName ?? basename($filePath));
		if ($size = filesize($filePath)) {
			$headers["Content-Length"] = $size;
		}

		if (($f = fopen($filePath, "r")) === false) {
			throw new ResponseException(
				sprintf("Unable to open the file \"%s\" for reading", $filePath),
				$this
			);
		}

		parent::__construct($status, $headers, stream_for($f), $version, $reason);
	}
}