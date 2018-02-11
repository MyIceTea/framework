<?php

namespace EsTeh\Routing;

use EsTeh\Hub\Singleton;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Routing
 * @license MIT
 */
final class RouteNaming
{
	use Singleton;

	private $container = [];

	private $containerOffset = -1;

	private $isNameHasBeenSet = false;

	public function __construct($uri)
	{
		self::$__instances[self::class] = $this;
		$this->seekContainerOffset();
		$this->isNameHasBeenSet = false;
		$this->container[$this->containerOffset] = [
			"name" => $uri,
			"uri"  => $uri
		];
	}

	public function name($name)
	{
		if ($this->isNameHasBeenSet === true) {
			$this->container[$this->containerOffset]["name"] .= ".".$name;
		} else {
			$this->container[$this->containerOffset]["name"] = $name;
			$this->isNameHasBeenSet = true;
		}
		return $this;
	}

	private function seekContainerOffset()
	{
		$this->containerOffset++;
	}

	public static function buildRouteNames()
	{
		$ins = self::getInstance();
		foreach($ins->container as $route) {
			$result[$route["name"]] = $route["uri"];
		}
		unset($ins->container, $ins->containerOffset, $ins->isNameHasBeenSet);
		return $result;
	}
}
