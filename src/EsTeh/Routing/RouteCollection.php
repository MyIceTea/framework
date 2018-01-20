<?php

namespace EsTeh\Routing;

use EsTeh\Hub\Singleton;

class RouteCollection
{
	use Singleton;

	private $routes = [];

	public static function setRoute($uri, $action, $method)
	{
		return self::getInstance()->add($uri, $action, $method);
	}

	public function add($uri, $action, $method)
	{
		do {
			$uri = str_replace('//', '/', $uri, $n);
		} while ($n);
		$uri = trim($uri, '/');
		if (is_array($action)) {
			if (isset($action['uses'])) {
				$this->routes[$uri][$method] = $action['uses'];
			}
		} else {
			$this->routes[$uri][$method] = $action;
		}
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
