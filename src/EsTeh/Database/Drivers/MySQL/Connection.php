<?php

namespace EsTeh\Database\Drivers\MySQL;

use EsTeh\Support\Config;
use EsTeh\Database\Drivers\MySQL\PDO;
use EsTeh\Contracts\Database\DriverContract;
use EsTeh\Database\Drivers\MySQL\QueryBuilder;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Database\Drivers\MySQL
 * @license MIT
 */
class Connection implements DriverContract
{
	private $pdo;

	public function __construct()
	{
		$cfg = Config::get("database")[Config::get("database")["connection"]];
		$this->pdo = new PDO(
			"mysql:".
				"host=".$cfg["host"].";".
				"port=".$cfg["port"].";".
				"dbname=".$cfg["dbname"],
			$cfg["user"],
			$cfg["pass"]
		);
	}

	public function getPdo()
	{
		return $this->pdo;
	}

	public function execMethod($method, $parameters)
	{
		if (is_callable([QueryBuilder::class, $method])) {
			return (new QueryBuilder($this->pdo))->{$method}(...$parameters);
		}
		return $this->pdo->{$method}(...$parameters);
	}

	public function connection()
	{
		return $this;
	}
}
