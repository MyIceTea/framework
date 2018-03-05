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
			$this->routes[$route][$method] = [
				"action" => $act,
				"middleware" => $this->getMiddleware(),
			];
			
			if (isset($action["as"])) {
				return $this->routeNaming->route($route)->name($action["as"]);
			}
			return $this->routeNaming->route($route);
		} else {
			$this->routes[$route][$method] = [
				"action" => $action,
				"middleware" => $this->getMiddleware(),
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
