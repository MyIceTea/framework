<?php

namespace EsTeh\Support;

use EsTeh\Hub\Singleton;
use EsTeh\Exception\ConfigException;

class Config
{
	use Singleton;

	private $mainConfig = [];

	public function __construct()
	{
	}

	/**
	 *
	 * @param string $key
	 * @return &mixed
	 */
	public static function &get($key)
	{
		$ins = self::getInstance();
		$key = explode(".", $key, 2);
		if (file_exists($file = config_path($key[0].'.php'))) {
			if (! array_key_exists($file, $ins->mainConfig)) {
				$ins->mainConfig[$file] = require $file;
			}
			if (count($key) > 1 && $key[1] !== "") {
				if (isset($ins->mainConfig[$file][$key[1]])) {
					return $ins->mainConfig[$file][$key[1]];
				} else {
					throw new ConfigException("Undefined index [{$key[1]}] in config file [{$file}]");
				}
			} else {
				return $ins->mainConfig[$file];
			}
		} else {
			throw new ConfigException("Config file [{$file}] does not exists!");
		}
	}

	public static function getAll()
	{
		return self::getInstance()->mainConfig;
	}
}
