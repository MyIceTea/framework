<?php

namespace EsTeh\Session;

use EsTeh\Security\Encryption\IceCrypt\IceCrypt;

class Session
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
	private $sessionId;

	/**
	 * @var array
	 */
	private $container = [];

	/**
	 * @var string
	 */
	private $sessionStorage;

	/**
	 * @var bool
	 */
	private $destroyed = false;

	/**
	 * @var array
	 */
	private $magicVars = [];

	/**
	 * Constructor.
	 *
	 * @param array $config
	 */
	public function __construct()
	{
		$this->key  = config("app.key");
		$cf = config("session");
		$this->name = $cf["cookie_name"];
		$this->expired = $cf["expired"];
		$this->path = $cf["cookie_path"];
		$this->domain = $cf["cookie_domain"];
		$this->secure = $cf["cookie_secure"];
		$this->httpOnly = $cf["cookie_http_only"];
		$this->sessionStorage = $cf["session_storage"];
		$this->init();
	}

	public function set($key, $value)
	{
		$this->container[$key] = $value;
		return $this;
	}

	public function get($key)
	{
		return array_key_exists($key, $this->container) ? $this->container[$key] : null;
	}

	public function flash($data)
	{
		$this->magicVars["flash"] = [0, $data];
	}

	public function getFlash()
	{
		return isset($this->container["__magic_vars"]["flash"][1]) ? $this->container["__magic_vars"]["flash"][1] : null;
	}

	public function unset($key)
	{
		unset($this->container[$key]);
		return $this;
	}

	public function destroy()
	{
		$this->destroyed = true;
		$this->container = null;
	}

	private function init()
	{
		if (isset($_COOKIE[$this->name])) {
			$this->sessionId = ice_decrypt($_COOKIE[$this->name], $this->key);
			$this->loadContainer();
		} else {
			$this->sessionId = rstr(32);
			$this->makeCookie();
		}
	}

	public function __destruct()
	{
		if ($this->destroyed) {
			setcookie(
				$this->name, 
				null,
				0,
				$this->path,
				$this->domain,
				$this->secure,
				$this->httpOnly
			);
			@unlink($this->sessionStorage."/".$this->sessionId);
		} else {
			$this->container["__magic_vars"] = $this->magicVars;
			file_put_contents(
				$this->sessionStorage."/".$this->sessionId, 
				ice_encrypt(
					serialize($this->container), 
					$this->key, 
					false
				), 
				LOCK_EX
			);
		}
	}

	private function loadContainer()
	{
		if (file_exists($this->sessionStorage."/".$this->sessionId)) {
			$this->container = unserialize(
					ice_decrypt(
						file_get_contents(
							$this->sessionStorage."/".$this->sessionId
						),
						$this->key,
						false
					)
				);
			if ($this->container === false) {
				$this->container = [];
			} else {
				if (isset($this->container["__magic_vars"]["flash"][0])) {
					if ($this->container["__magic_vars"]["flash"][0]) {
						unset($this->container["__magic_vars"]["flash"]);
					} else {
						$this->container["__magic_vars"]["flash"][0] = 1;
					}
				}
			}
		}
	}

	private function makeCookie()
	{
		setcookie(
			$this->name, 
			ice_encrypt($this->sessionId, $this->key),
			time()+$this->expired+60,
			$this->path,
			$this->domain,
			$this->secure,
			$this->httpOnly
		);
	}
}
