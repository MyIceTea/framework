<?php

namespace EsTeh\Http;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Http
 * @license MIT
 */
class Request
{
	private $headers = [];

	private $rawInput;

	public function __construct()
	{
		$this->headers = getallheaders();
		$this->rawInput = file_get_contents("php://input");
	}

	public function getJson()
	{
		return json_decode($this->rawInput, true);
	}

	public function method()
	{
		return $_SERVER["REQUEST_METHOD"];
	}

	public function header($key = null)
	{
		if (is_null($key)) {
			return $this->headers;
		} elseif (is_array($key)) {
			$r = [];
			foreach ($this->headers as $key => $value) {
				if ($s = array_search($key, $this->headers)) {
					$r[$key] = $this->headers[$s];
				}
			}
			return $r;
		}
		return array_key_exists($key, $this->headers) ? $this->headers[$key] : null;
	}
}
