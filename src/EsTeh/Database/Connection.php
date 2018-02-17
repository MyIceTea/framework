<?php

namespace EsTeh\Database;

use EsTeh\Hub\Singleton;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Database
 * @license MIT
 */
class Connection
{
	use Singleton;

	private $pdo;

	protected function __construct()
	{
		
	}

	public function getPdo()
	{
		return $this->pdo;
	}
}
