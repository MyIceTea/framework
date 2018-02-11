<?php

namespace EsTeh\Session;

use EsTeh\Hub\Singleton;
use EsTeh\Session\Session;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Session
 * @license MIT
 */
class SessionHandler
{
	public static function getHandlerInstance()
	{
		return self::getInstance();
	}

	public function __call($method, $parameters)
	{
		return Session::getInstance()->{$method}(...$parameters);
	}

	public static function __callStatic($method, $parameters)
	{
		return Session::getInstance()->{$method}(...$parameters);
	}

	public function batchSet($data = [])
	{
		$ins = Session::getInstance();
		if (is_array($data)) {
			foreach ($data as $key => $val) {
				$ins->set($key, $val);
			}
		}
	}
}
