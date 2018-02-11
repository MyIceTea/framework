<?php

namespace EsTeh\Console;

use ArrayAccess;
use EsTeh\Support\ArrayUtils;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Console
 * @license MIT
 */
class ArgvInput implements ArrayAccess
{
	use ArrayUtils;

	public function __construct()
	{
		$argv = $_SERVER['argv'];
		array_shift($argv);
		foreach ($argv as $key => $value) {
			$this[$key] = $value;
		}
	}
}
