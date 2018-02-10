<?php

namespace EsTeh\Console;

use ArrayAccess;
use EsTeh\Support\ArrayUtils;

class ArgvInput implements ArrayAccess
{
	use ArrayUtils;

	public function __construct()
	{
		//$argv = $_SERVER['argv'];
		var_dump($argv);
		array_shift($argv);
		foreach ($argv as $key => $value) {
			$this[$key] = $value;
		}
	}
}
