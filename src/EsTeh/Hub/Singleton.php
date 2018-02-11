<?php

namespace EsTeh\Hub;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Hub
 * @license MIT
 */
trait Singleton
{
	/**
	 * @var array
	 */
	protected static $__instances = [];

	/**
	 *
	 * @param mixed ...$parameters
	 * @return static
	 */
	public static function &getInstance(...$parameters)
	{
		if (! isset(static::$__instances[static::class])) {
			static::$__instances[static::class] = new static(...$parameters);
		}
		return static::$__instances[static::class];
	}
}
