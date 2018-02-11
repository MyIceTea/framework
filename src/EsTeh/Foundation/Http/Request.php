<?php

namespace EsTeh\Foundation\Http;

use EsTeh\Foundation\HttpCapture;
use EsTeh\Contracts\Abilities\Jsonable;
use EsTeh\Contracts\Abilities\Arrayable;
use EsTeh\Contracts\Abilities\Stringable;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Foundation\Http
 * @license MIT
 */
class Request extends HttpCapture implements Jsonable, Arrayable, Stringable
{
	/**
	 * @var array
	 */
	private $container = [];

	/**
	 * @var array
	 */
	private $jsonInput = [];

	/**
	 * Constructor.
	 */
	protected function __construct()
	{
		$this->container = [
			"header" => getallheaders(),
			"input" => [
				"raw" => file_get_contents("php://input"),
				"post" => isset($_POST) ? $_POST : [],
				"files" => isset($_FILES) ? $_FILES : [],
				"get" => isset($_GET) ? $_GET : []
			],
			"method" => isset($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : "GET"
		];		

		$this->captureStatus = true;
	}

	public static function getMethod()
	{
		return self::getInstance()->container["method"];
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return $this->container;
	}

	/**
	 * @return string
	 */
	public function toJson()
	{
		return json_encode($this->toArray());
	}

	/**
	 * @return \StdClass|null
	 */
	public function toObject()
	{
		return json_decode($this->toJson());
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->toJson();
	}

	/**
	 * @return array
	 */
	public static function getAllHeaders()
	{
		return self::getStaticInstance()->container["header"];
	}

	/**
	 * @return string
	 */
	public static function getRawInput()
	{
		return self::getStaticInstance()->container["input"]["raw"];
	}

	/**
	 * @return array
	 */
	public static function get($key)
	{
		$ins = self::getInstance();
		return array_key_exists($key, $ins->container["input"]["post"]) ? $ins->container["input"]["post"][$key] : null;
	}

	public function except($key)
	{
		$r = [];
		if (is_array($this->container["input"]["post"])) {
			if (is_array($key)) {
				foreach ($this->container["input"]["post"] as $key2 => $val) {
					if (! in_array($key2, $key)) {
						$r[$key2] = $val;
					}
				}
			} else {
				$r = $this->container["input"]["post"];
				unset($r[$key]);
			}
		}
		return $r;
	}

	/**
	 * @return mixed
	 */
	public static function getJson()
	{
		$ins = self::getStaticInstance();
		if (! $ins->hasBuiltJson) {
			$ins->buildJson();
		}
		return array_key_exists($key, $ins->jsonInput)  ? $ins->jsonInput[$key] : null;
	}

	/**
	 * Build json input.
	 */
	private function buildJson()
	{
		$this->jsonInput = json_decode($this->container["input"]["raw"], true);
		$this->jsonInput = is_array($this->jsonInput) ? $this->jsonInput : [];
		$this->hasBuiltJson = true;
	}
}
