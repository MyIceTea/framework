<?php

namespace EsTeh\Foundation\Http;

use EsTeh\Routing\Router;
use EsTeh\Foundation\HttpCapture;
use EsTeh\Contracts\Abilities\Jsonable;
use EsTeh\Contracts\Abilities\Arrayable;
use EsTeh\Contracts\Abilities\Stringable;

class Route extends HttpCapture implements Jsonable, Arrayable, Stringable
{

	private $container = [];

	/**
	 * Constructor.
	 */
	protected function __construct()
	{
		$this->currentRoute = Router::capture();
		$this->captureStatus = true;
	}

	public static function getCurrentRoute()
	{
		return self::getInstance()->currentRoute;
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

}
