<?php

namespace EsTeh\Database\Drivers;

use EsTeh\Hub\Singleton;

class Map
{
	public static $driver = [
		"mysql" => \EsTeh\Database\Drivers\MySQL\Connection::class
	];
}