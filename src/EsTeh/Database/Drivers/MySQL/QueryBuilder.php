<?php

namespace EsTeh\Database\Drivers\MySQL;

use PDO;
use PDOException;
use EsTeh\Database\Connection;
use EsTeh\Database\Drivers\MySQL\PDO as PDOConnection;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Database\Drivers\MySQL
 * @license MIT
 */
class QueryBuilder
{
	/**
	 * @var \EsTeh\Database\Drivers\MySQL\PDO
	 */
	private $pdo;

	/**
	 * @var array
	 */
	private $where;

	/**
	 * @var string
	 */
	private $table;

	/**
	 * @var int
	 */
	private $limit;

	/**
	 * @var int
	 */
	private $offset;

	/**
	 * @var array
	 */
	private $select;

	/**
	 * @var string
	 */
	private $groupBy;

	/**
	 * @var string
	 */
	private $orderBy;

	/**
	 * @var string
	 */
	private $orderType = "ASC";

	/**
	 * @var array
	 */
	private $bindedValue = [];

	/**
	 * Constructor.
	 *
	 * @param \EsTeh\Database\Drivers\MySQL\PDO $pdo
	 * @return void
	 */
	public function __construct(PDOConnection $pdo)
	{
		$this->pdo = $pdo;
	}

	/**
	 * @param string $rawQuery
	 * @return array
	 */
	public function raw($rawQuery)
	{
		return [$rawQuery];
	}

	/**
	 * @param string $table
	 * @return $this
	 */
	public function table($table)
	{
		$this->table = "`{$table}`";
		return $this;
	}

	/**
	 * @param string $coulum
	 * @return $this
	 */
	public function select($coulum)
	{
		if (is_array($coulum)) {
			$this->select = $coulum;
		} else {
			$this->select = func_get_args();
		}
		return $this;
	}

	/**
	 * @param string $coulum
	 * @param string $type
	 * @return $this
	 */
	public function orderBy($coulum, $type = "ASC")
	{
		$this->orderBy = $coulum;
		$this->type = strtoupper($type);
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get()
	{
		$st = $this->prepare($this->toSql());
		$st->execute($this->bindedValue);
		$err = $st->errorInfo();
		if ($err[1]) {
			Connection::terminateConnection(
				new PDOException("[{$err[1]}]: {$err[2]}")
			);
		}
		return $st->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	 * @return mixed
	 */
	public function first()
	{
		if (! is_int($this->limit)) {
			$this->limit = 1;
		}
		$st = $this->prepare($this->toSql());
		$st->execute($this->bindedValue);
		$err = $st->errorInfo();
		if ($err[1]) {
			Connection::terminateConnection(
				new PDOException("[{$err[1]}]: {$err[2]}")
			);
		}
		return $st->fetch(PDO::FETCH_OBJ);
	}

	/**
	 * @param string $statement
	 * @return \PDOStatement
	 */
	private function prepare($statement)
	{
		return $this->pdo->prepare($statement);
	}

	/**
	 * @param string $col
	 * @param string $neg
	 * @param strung $val
	 * @return $this
	 */
	public function where($col, $neg, $val)
	{
		if (is_null($val)) {
			if ($neg === "=") {
				$neg = "IS NULL";
			} elseif ($neg === "!=") {
				$neg = "IS NOT NULL";
			}
		}
		$this->where[] = [
			"type" => "AND",
			"coulum" => $col,
			"neg" => $neg,
			"value" => $val
		];
		return $this;
	}

	/**
	 * @param string $col
	 * @param string $neg
	 * @param strung $val
	 * @return $this
	 */
	public function orWhere($col, $neg, $val)
	{
		if (is_null($val)) {
			if ($neg === "=") {
				$neg = "IS NULL";
			} elseif ($neg === "!=") {
				$neg = "IS NOT NULL";
			}
		}
		$this->where[] = [
			"type" => "OR",
			"coulum" => $col,
			"neg" => $neg,
			"value" => $val
		];
		return $this;
	}

	/**
	 * @param int $int
	 * @return $this
	 */
	public function offset($int)
	{
		$this->offset = $int;
		return $this;
	}

	/**
	 * @param int $int
	 * @return $this
	 */
	public function limit($int)
	{
		$this->limit = $int;
		return $this;
	}

	/**
	 * @return string
	 */
	public function toSql()
	{
		$query = "";
		if (is_array($this->select)) {

			$this->buildSelectClause($query);

			// build where clause
			if (is_array($this->where)) {
				$this->buildWhereClause($query);
			}

			if (is_string($this->orderBy)) {
				$query .= "ORDER BY `{$this->orderBy}` {$this->orderType} ";
			}

			// build limit clause
			if (is_int($this->limit)) {
				$query .= "LIMIT {$this->limit} ";
			}

			// build offset clause
			if (is_int($this->offset)) {
				$query .= "OFFSET {$this->offset} ";
			}
		}
		return $query;
	}

	/**
	 * @param string &$query
	 * @return void
	 */
	private function buildSelectClause(&$query)
	{
		// build select clause
		array_walk($this->select, function (&$a) {
			if (is_array($a)) {
				$a = $a[0];
				return;
			}
			$s = preg_split("/\sas\s/Ui", $a);
			if (count($s) === 2) {
				$a = "`{$s[0]}` AS `{$s[1]}`";
				return;
			}
			$a = "`{$a}`";
		});
		$this->select = implode(",", $this->select);
		if (empty($this->select)) {
			$this->select = "*";
		}
		$query .= "SELECT {$this->select} FROM {$this->table} ";
	}

	/**
	 * @param string &$query
	 * @return void
	 */
	private function buildWhereClause(&$query)
	{
		$query .= "WHERE ";
		foreach ($this->where as $key => $val) {
			$query .= "`".$val["coulum"]."` ";
			$query .= $val["neg"]." ";
			if (! is_null($val["value"])) {
				$i = 0;
				$col = ":".$val["coulum"];
				do {
					$i++;
				} while (array_key_exists($col."_".$i, $this->bindedValue) or !($val["coulum"] = $col."_".$i));
				$query .= $val["coulum"]." ";
				$this->bindedValue[$val["coulum"]] = $val["value"];
			}
			if (isset($this->where[$key + 1])) {
				$query .= $this->where[$key + 1]["type"]." ";
			}
		}
	}
}
