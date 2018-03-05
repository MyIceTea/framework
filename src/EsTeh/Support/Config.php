<?php

namespace EsTeh\Support;

use EsTeh\Exception\ConfigException;

class Config
{
	/**
	 * @var string
	 */
	private $configPath;

	/**
	 * @var array
	 */
	private $cachedConfig = [];

	/**
	 * Constructor.
	 *
	 * @param string $configPath
	 * @return void
	 */
	public function __construct($configPath)
	{
		$this->configPath = $configPath;
	}

	public function get($key)
	{
		$key = explode(".", $key);
		if (! isset($this)) {
			dd(debug_backtrace());
		}
		if (! isset($this->cachedConfig[$key[0]])) {
			if (! file_exists($f = $this->configPath."/".$key[0].".php")) {
				throw new ConfigException("Config file [{$key[0]}] does not exist");
			}
			$this->cachedConfig[$key[0]] = include $f;
		}
		if (count($key) == 1) {
			return $this->cachedConfig[$key[0]];
		}
		
		$f = $this->cachedConfig[$key[0]];
		array_shift($key);
		foreach ($key as $k) {
			$f = $f[$k];
		}
		return $f;
	}
}
