<?php

namespace EsTeh\Http;

class CsrfFactory
{
	/**
	 * @var string
	 */
	private $key;

	/**
	 * @var int
	 */
	private $expired;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var string
	 */
	private $domain;

	/**
	 * @var bool
	 */
	private $secure;

	/**
	 * @var bool
	 */
	private $httpOnly = false;

	/**
	 * @var string
	 */
	private $token;


	/**
	 * Constructor.
	 *
	 * @param array $cf
	 */
	public function __construct($cf, $run = 1)
	{
		$this->key  = config("app.key");
		$this->name = $cf["cookie_name"];
		$this->expired = $cf["expired"];
		$this->path = $cf["cookie_path"];
		$this->domain = $cf["cookie_domain"];
		$this->secure = $cf["cookie_secure"];
		$this->httpOnly = $cf["cookie_http_only"];
		if ($run) {
			$this->init();	
		}
	}

	public function reinitialize()
	{
		$this->init();
	}

	private function init()
	{
		$this->token = rstr(32);
		$this->makeCookie();
	}

	public function getToken()
	{
		return $this->token;
	}

	private function makeCookie()
	{
		setcookie(
			$this->name, 
			ice_encrypt(json_encode(
				[
					"token" => $this->token,
					"expired" => time()+$this->expired
				]
			), $this->key),
			time()+$this->expired+3600,
			$this->path,
			$this->domain,
			$this->secure,
			$this->httpOnly
		);
	}
}
