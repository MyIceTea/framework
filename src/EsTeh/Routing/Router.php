<?php

namespace EsTeh\Routing;

use EsTeh\Routing\RouteCollection;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Routing
 * @license MIT
 */
class Router
{
	private $routeCollection;

	public function __construct()
	{
		$this->routeCollection = new RouteCollection;
	}

	public function collect($method, $route, $action)
	{
		return $this->routeCollection->collect($method, $route, $action);
	}

	public function openGroup($group, $action = null)
	{
		return $this->routeCollection->openGroup($group, $action);
	}

	public function closeGroup()
	{
		return $this->routeCollection->closeGroup();
	}

	public function handle()
	{
		$matcher = new RouteMatching($this->routeCollection->getRoutes());
		return $matcher->getAction();
	}
}
