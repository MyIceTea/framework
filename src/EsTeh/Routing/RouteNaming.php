<?php

namespace EsTeh\Routing;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Routing
 * @license MIT
 */
class RouteNaming
{
	private $container = [];

	private $offset;

	public function route($offset)
	{
		$this->offset = $offset;
		return $this;
	}

	public function name($name)
	{
		if (isset($this->container[$this->offset])) {
			$this->container[$this->offset] .= ".".$name;
		}
		$this->container[$this->offset] = $name;
		return $this;
	}
}
