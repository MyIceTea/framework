<?php

namespace EsTeh\Routing;

use ArrayAccess;
use EsTeh\Hub\Singleton;
use EsTeh\Support\ArrayUtils;
use EsTeh\Contracts\Abilities\Arrayable;

class RouteBinding implements ArrayAccess, Arrayable
{
	use Singleton, ArrayUtils;

	public static function set($key, $val)
	{
		$ins = self::getInstance();
		$ins[$key] = $val;
	}
}
