<?php

namespace EsTeh\Routing;

use EsTeh\Routing\RouteBinding;
use EsTeh\Foundation\Http\Route;
use EsTeh\Foundation\Http\Request;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Routing
 * @license MIT
 */
class RouteMatching
{
	private $match;

	private $routes = [];

	private $currentRoute;	

	public function __construct($routes)
	{
		$this->routes = $routes;
		$this->currentRoute = Route::getCurrentRoute();
	}

	public function uri()
	{
		do {
			$this->currentRoute = trim(str_replace("//", "/", $this->currentRoute, $n), "/");
		} while ($n);
		if (isset($this->routes[$this->currentRoute])) {
			$this->match = $this->routes[$this->currentRoute];
			return true;
		}
		$cUri = explode("/", $this->currentRoute);
		$cUriCount = count($cUri);
		foreach ($this->routes as $uri => $action) {
			if (strpos($uri, "{") !== false && strpos($uri, "}")) {
				$uri = explode("/", $uri);
				if (count($uri) === $cUriCount) {
					foreach ($uri as $num => $segment) {
						$segmentLength = strlen($segment) - 1;
						if ($segment[0] === "{" && $segment[$segmentLength] === "}") {
							RouteBinding::set(substr($segment, 1, -1), $cUri[$num]);
						}
					}
					$this->match = $action;
					return true;
				}
			}
		}
		return false;
	}

	public function method()
	{
		if (isset($this->match[$method = Request::getMethod()])) {
			$this->match = $this->match[$method];
			return true;
		}
		if (isset($this->match["*"])) {
			$this->match = $this->match["*"];
			return true;
		}
		return false;
	}

	public function getAction()
	{
		return $this->match;
	}
}
