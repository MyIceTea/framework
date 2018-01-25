<?php

namespace EsTeh\Session;

use EsTeh\Hub\Singleton;
use EsTeh\Session\Session;

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
