<?php

namespace EsTeh\Support;

use EsTeh\Hub\Singleton;

class Config
{
	use Singleton;

	public function __construct()
	{
	}

	/**
	 *
	 * @param string $key
	 * @return mixed
	 */
	public static function get($key)
	{
		$ins = self::getInstance();
		$key = explode(".", $key);
		if (! isset($ins->configFile[$key[0]])) {
			$ins->configFile[$key[0]] = require base_path('config/'.$key[0].'.php');
		}
		$offset = $key[0];
		unset($key[0]);
		return self::recursiveGet($key, $ins->configFile[$offset]);
	}

	/**
	 * 
	 */
	private static function recursiveGet()
	{
	}
}
