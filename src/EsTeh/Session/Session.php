<?php

namespace EsTeh\Session;

use EsTeh\Hub\Singleton;
use EsTeh\Support\Config;

class Session
{
	use Singleton;

	private $file;

	private $session = [];

	private $cookieId;

	private $cookieName;

	private $cookieExpired;

	protected function __construct()
	{
		$config = Config::get('app.session');
		$this->cookieName = $config['cookie_name'];
		$this->cookieExpired = $config['expired'];
		$oldCookie = isset($_COOKIE[$this->cookieName]);
		$this->cookieId = $oldCookie ? $_COOKIE[$this->cookieName] : rstr(32);
		$this->file = storage_path('framework/sessions/'.$this->cookieId);
		if ($oldCookie) {
			if (file_exists($this->file)) {
				$this->session = unserialize(file_get_contents($file));
			}
		} else {
			setcookie($this->cookieName, $this->cookieId, time() + $this->cookieExpired);
		}
	}

	public static function set($key, $val)
	{
		$ins = self::getInstance();
		$ins->session[$key] = $val;
	}

	public static function &get($key)
	{
		$ins = self::getInstance();
		return array_key_exists($key, $ins->session) ? $ins->session[$key] : $ins->__;
	}

	public function __get($key)
	{
		return null;
	}

	public function __destruct()
	{
		file_put_contents($this->file, serialize($this->session));
	}
}