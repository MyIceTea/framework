<?php

namespace EsTeh\Routing;

use ArrayAccess;
use EsTeh\Hub\Singleton;
use EsTeh\Support\ArrayUtils;
use EsTeh\Contracts\Abilities\Arrayable;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Routing
 * @license MIT
 */
class RouteBinding implements ArrayAccess, Arrayable
{
	use Singleton, ArrayUtils;

	public static function set($key, $val)
	{
		$ins = self::getInstance();
		$ins[$key] = $val;
	}
}
