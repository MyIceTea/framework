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
	private $joins = [];

	/**
	 * @var array
	 */
	private $where = [];

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
		$this->table = "{$table}";
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
	 * @param string $coulum
	 * @param string $type
	 * @return $this
	 */
	public function groupBy($coulum, $type = "ASC")
	{
		$this->groupBy = $coulum;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get()
	{
		$st = $this->prepare($this->toSql());
		try {
			$st->execute($this->bindedValue);	
		} catch (PDOException $e) {
			Connection::terminateErrorQuery($e);	
		}
		return $st->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	 * @param string $table
	 * @param string $foreign
	 */
	public function join($table, $foreign = null, $neg = null, $to = null, $type = "INNER")
	{
		$this->joins[] = [
			"table" => $table,
			"foreign" => $foreign,
			"neg" => $neg,
			"to" => $to,
			"type" => strtoupper($type)
		];		
		return $this;
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
		try {
			$exe = $st->execute($this->bindedValue);	
		} catch (PDOException $e) {
			Connection::terminateErrorQuery($e);	
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
	 * @param array $update
	 * @return bool
	 */
	public function update($update)
	{
		$query = "UPDATE `{$this->table}` SET ";
		foreach ($update as $key => $val) {
			if (! is_null($val)) {
				$i = 0;
				$query .= $this->sanitizeColNoRef($key)." = ";
				$col = ":".$key;
				do {
					$i++;
				} while (array_key_exists($col."_".$i, $this->bindedValue) or !($key = $col."_".$i));
				$query .= $key." ";
				$this->bindedValue[$key] = $val;
			}	
		}
		if ($this->where) {
			$this->buildWhereClause($query);
		}
		// build `where` clause
		if ($this->where) {
			$this->buildWhereClause($query);
		}

		// build `group by` clause
		if (is_string($this->groupBy)) {
			$this->buildGroupByClause($query);
		}

		// build `order by` clause
		if (is_string($this->orderBy)) {
			$this->buildOrderByClause($query);
		}

		// build `limit` clause
		if (is_int($this->limit)) {
			$query .= "LIMIT {$this->limit} ";
		}

		// build `offset` clause
		if (is_int($this->offset)) {
			$query .= "OFFSET {$this->offset} ";
		}

		$st = $this->prepare(trim($query).";");
		try {
			$exe = $st->execute($this->bindedValue);	
		} catch (PDOException $e) {
			Connection::terminateErrorQuery($e);	
		}
		return $exe;
	}

	/**
	 * @return bool
	 */
	public function delete()
	{
		$query = "DELETE FROM `{$this->table}` ";
		if ($this->where) {
			$this->buildWhereClause($query);
		}
		// build `where` clause
		if ($this->where) {
			$this->buildWhereClause($query);
		}

		// build `group by` clause
		if (is_string($this->groupBy)) {
			$this->buildGroupByClause($query);
		}

		// build `order by` clause
		if (is_string($this->orderBy)) {
			$this->buildOrderByClause($query);
		}

		// build `limit` clause
		if (is_int($this->limit)) {
			$query .= "LIMIT {$this->limit} ";
		}

		// build `offset` clause
		if (is_int($this->offset)) {
			$query .= "OFFSET {$this->offset} ";
		}

		$st = $this->prepare(trim($query).";");
		try {
			$exe = $st->execute($this->bindedValue);	
		} catch (PDOException $e) {
			Connection::terminateErrorQuery($e);	
		}
		return $exe;
	}

	/**
	 * @return bool
	 */
	public function truncate()
	{
		$st = $this->prepare("TRUNCATE TABLE `{$this->table}`;");
		try {
			$exe = $st->execute();	
		} catch (PDOException $e) {
			Connection::terminateErrorQuery($e);	
		}
		return $exe;
	}

	/**
	 * @return string
	 */
	public function toSql()
	{
		$query = "";
		if (is_array($this->select)) {
			$this->buildSelectClause($query);
		} else {
			$query = "SELECT * FROM `{$this->table}` ";
		}
		// build `join` clause
		if ($this->joins) {
			$this->buildJoinClause($query);
		}

		// build `where` clause
		if ($this->where) {
			$this->buildWhereClause($query);
		}

		// build `group by` clause
		if (is_string($this->groupBy)) {
			$this->buildGroupByClause($query);
		}

		// build `order by` clause
		if (is_string($this->orderBy)) {
			$this->buildOrderByClause($query);
		}

		// build `limit` clause
		if (is_int($this->limit)) {
			$query .= "LIMIT {$this->limit} ";
		}

		// build `offset` clause
		if (is_int($this->offset)) {
			$query .= "OFFSET {$this->offset} ";
		}
		return trim($query).";";
	}

	/**
	 * @param string &$query
	 * @return void
	 */
	private function buildJoinClause(&$query)
	{
		foreach ($this->joins as $val) {
			$query .= $val["type"]." JOIN `".$val["table"]."` ";
			if (! is_null($val["to"])) {
				$this->sanitizeCol($val["foreign"]);
				$this->sanitizeCol($val["to"]);
				$query .= "ON ".$val["foreign"]." ".$val["neg"]." ".$val["to"]." ";
			}
		}
	}

	/**
	 * @param string &$query
	 * @return void
	 */
	private function buildOrderByClause(&$query)
	{
		$query .= "ORDER BY `{$this->orderBy}` {$this->orderType} ";
	}

	/**
	 * @param string &$query
	 * @return void
	 */
	private function buildGroupByClause(&$query)
	{
		$query .= "GROUP BY `{$this->groupBy}` ";
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
				$this->sanitizeCol($s[0]);
				$a = "{$s[0]} AS `{$s[1]}`";
				return;
			}
			$this->sanitizeCol($a);
		});
		$this->select = implode(",", $this->select);
		if (empty($this->select)) {
			$this->select = "*";
		}
		$query .= "SELECT {$this->select} FROM `{$this->table}` ";
	}

	/**
	 * @param string &$arg
	 * @return void
	 */
	private function sanitizeCol(&$arg)
	{
		$arg = explode(".", $arg);
		array_walk($arg, function (&$arg) {
			$arg = "`{$arg}`";
		});
		$arg = implode(".", $arg);
	}

	/**
	 * @param string &$arg
	 * @return void
	 */
	private function sanitizeColNoRef($arg)
	{
		$arg = explode(".", $arg);
		array_walk($arg, function (&$arg) {
			$arg = "`{$arg}`";
		});
		$arg = implode(".", $arg);
		return $arg;
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
