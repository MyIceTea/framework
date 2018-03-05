<?php

namespace EsTeh\Routing;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Routing
 * @license MIT
 */
class RouteCollection
{
	private $prefix = ["/"];

	private $routes = [];

	private $groupState = [];

	private $arrayOffset = -1;

	private $routeNaming;

	private $groupMiddleware = [];

	private $middlewareOffset = -1;

	private $prefixOffset = -1;

	public function __construct()
	{
		$this->routeNaming = new RouteNaming;
	}

	public function collect($method, $route, $action)
	{
		$this->arrayOffset++;
		$route = "/".trim(implode("/", $this->prefix)."/".$route, "/");
		do {
			$route = str_replace("//", "/", $route, $n);
		} while ($n);
		if (is_array($action)) {
			$act = null;
			if (isset($action["uses"])) {
				$act = $action["uses"];
			} elseif (isset($action["use"])) {
				$act = $action["use"];
			}
			$this->routes[$this->arrayOffset] = [
				"uri" => $route,
				"action" => $act,
				"middleware" => $this->getMiddleware(),
				"method" => $method
			];
			
			if (isset($action["as"])) {
				return $this->routeNaming->route($route)->name($action["as"]);
			}
			return $this->routeNaming->route($route);
		} else {
			$this->routes[$this->arrayOffset] = [
				"uri" => $route,
				"action" => $action,
				"middleware" => $this->getMiddleware(),
				"method" => $method
			];
			return $this->routeNaming->route($route);
		}
	}

	private function getMiddleware()
	{
		return $this->groupMiddleware;
	}

	public function openGroup($group, $action = null)
	{
		$this->middlewareOffset++;
		$this->prefixOffset++;
		if (isset($group["middleware"])) {
			$this->groupMiddleware[$this->middlewareOffset] = $group["middleware"];
		}
		if (isset($group["prefix"])) {
			$this->prefix[$this->prefixOffset] = $group["prefix"];
		}
	}

	public function closeGroup()
	{
		unset($this->groupMiddleware[$this->middlewareOffset--]);
		unset($this->prefix[$this->prefixOffset--]);
	}

	public function getRoutes()
	{
		return $this->routes;
	}
}
