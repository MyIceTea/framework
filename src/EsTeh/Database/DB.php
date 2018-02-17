<?php

namespace EsTeh\Database;

use EsTeh\Database\Connection;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Database
 * @license MIT
 */
class DB
{
	public static function __callStatic($method, $parameters)
	{
		return self::getConnection()->call($method, $parameters);
	}

	protected static function getConnection()
	{
		return Connection::getInstance();
	}
}
