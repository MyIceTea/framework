<?php

namespace EsTeh\Database;

use PDOException;
use PDO as BasePDO;

class PDO
{
	private $pdo;

	public function __construct(...$pdoParams)
	{
		try {
			$this->pdo = new BasePDO(...$pdoParams);	
		} catch (PDOException $e) {
			
		}
	}

	public function getPdo()
	{
		
	}
}
