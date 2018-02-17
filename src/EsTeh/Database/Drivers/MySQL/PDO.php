<?php

namespace EsTeh\Database\Drivers\MySQL;

use PDOException;
use PDO as BasePDO;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Database\Drivers\MySQL
 * @license MIT
 */
class PDO
{
	private $pdo;

	public function __construct(...$pdoParams)
	{
		$this->pdo = new BasePDO(...$pdoParams);	
	}

	public function __call($method, $parameters)
	{
		return $this->pdo->{$method}(...$parameters);
	}
}
