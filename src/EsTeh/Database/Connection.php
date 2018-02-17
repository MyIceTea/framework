<?php

namespace EsTeh\Database;

use PDOException;
use EsTeh\Hub\Singleton;
use EsTeh\Support\Config;
use EsTeh\Database\Drivers\Map;
use EsTeh\Exception\Database\QueryException;
use EsTeh\Exceptions\Database\UnknownDriverException;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Database
 * @license MIT
 */
class Connection
{
	use Singleton;

	private $pdo;

	private $connection;

	protected function __construct()
	{
		$this->connection = Config::get("database")["connection"];
		$this->initConnection();
	}

	public function getPdo()
	{
		return $this->connection->getPdo();
	}

	public function call($method, $parameters)
	{
		try {
			$conn = $this->connection->execMethod($method, $parameters);
		} catch (PDOException $e) {
			self::terminateConnection($e);
		}
		return $conn;
	}

	private function initConnection()
	{
		if (! isset(Map::$driver[$this->connection])) {
			throw new UnknownDriverException("Driver [{$this->connection}] not found");
		}
		
		$this->connection = new Map::$driver[$this->connection];
	}

	public static function terminateConnection(PDOException $e)
	{
		throw new QueryException(
			$e->getMessage()
		);
	}
}
