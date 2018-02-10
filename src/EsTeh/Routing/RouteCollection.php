<?php

namespace EsTeh\Routing;

use EsTeh\Hub\Singleton;
use EsTeh\Routing\RouteNaming;

class RouteCollection
{
	use Singleton;

	private $routes = [];

	private $prefix = "";

	public static function setRoute($uri, $action, $method)
	{
		return self::getInstance()->add($uri, $action, $method);
	}

	public static function group($prefix)
	{
		self::getInstance()->prefix = $prefix;
	}

	public static function closeGroup()
	{
		self::getInstance()->prefix = "";
	}

	public function add($uri, $action, $method)
	{
		do {
			$uri = $this->prefix."/".str_replace("//", "/", $uri, $n);
		} while ($n);
		$uri = trim($uri, "/");
		if (is_array($action)) {
			if (isset($action["uses"])) {
				$this->routes[$uri][$method] = $action["uses"];
			} elseif (isset($action["use"])) {
				$this->routes[$uri][$method] = $action["use"];
			}
		} else {
			$this->routes[$uri][$method] = $action;
		}
		if (isset($this->routeNaming)) {
			$this->routeNaming->__construct($uri);
		} else {
			$this->routeNaming = new RouteNaming($uri);
		}
		return $this->routeNaming;
	}

	public static function getAll($destroy = false)
	{
		$routes =  self::getInstance()->routes;
		if ($destroy) {
			self::destroy();
		}
		return $routes;
	}

	public static function destroy()
	{
		self::getInstance()->routes = null;
	}
}
