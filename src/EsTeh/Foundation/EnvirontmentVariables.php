<?php

namespace EsTeh\Foundation;

use EsTeh\Hub\Singleton;
use EsTeh\Foundation\Application;

final class EnvirontmentVariables
{
	use Singleton;

	/**
	 * @var array
	 */
	private $env = [];

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->env = $this->parseEnvFile();
	}

	/**
	 * Parse .env file.
	 */
	private function parseEnvFile()
	{
		preg_match_all('/(.*)=(.*)\n/Us', file_get_contents(base_path('.env')), $matches);
		if (isset($matches[1], $matches[2])) {
			Application::setEnv(array_combine($matches[1], $matches[2]));
		}
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public static function get($key, $defaut = null)
	{
		return self::getInstance()->getEnv($key, $defaut);
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	private function getEnv($key, $defaut = null)
	{
		$env = Application::getInstance()->env;
		return array_key_exists($key, $env) ? $env[$key] : $default;
	}
}
