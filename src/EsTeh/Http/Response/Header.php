<?php

namespace EsTeh\Http\Response;

use EsTeh\Contracts\Http\Response;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Http\Response
 * @license MIT
 */
class Header implements Response
{
	private $headers = [];

	private $headerF = [];

	public function __construct($headerF)
	{
		$this->headerF = $headerF;
	}

	public function buildHeader()
	{
		$this->headers["X-EsTeh-Framework-Version"] = ESTEH_VERSION;
	}

	public function sendResponse()
	{
		http_response_code($this->headerF["http_response_code"]);
		foreach ($this->headers as $key => $val) {
			header($key.": ".$val);
		}
	}
}
