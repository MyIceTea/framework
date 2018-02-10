<?php

namespace EsTeh\Http;

use EsTeh\Hub\Singleton;

class CsrfFactory
{
	use Singleton;

	private $token;

	public static function initCsrfCookie($config)
	{
		$build = json_encode(
			[
				"expired" => time() + $config["expired"],
				"token" => self::generateCsrfToken()
			]
		);
		setcookie($config["cookie_name"], ice_encrypt($build, app_key()), time() + 3600 + $config["expired"], "/", $config["is_secure_cookie"]);
	}

	public static function generateCsrfToken()
	{
		$ins = self::getInstance();
		$ins->token = rstr(32, "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890_____.....---");
		return $ins->token;
	}

	public static function getToken()
	{
		return self::getInstance()->token;
	}
}
