<?php

namespace EsTeh\Hub;

trait Singleton
{
	/**
	 * @var self
	 */
	private static $instance;

	/**
	 * @param mixed ...$parameters
	 */
	public static function &getInstance(...$parameters)
	{
		if (self::$instance === null) {
			self::$instance = new self(...$parameters);
		}
		return self::$instance;
	}

	/**
	 * Prevent cloning instance.
	 */
	private function __clone()
	{
	}
}
