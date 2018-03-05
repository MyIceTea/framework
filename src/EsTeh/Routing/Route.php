<?php

namespace EsTeh\Routing;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Routing
 * @license MIT
 */
class Route
{
	/**
	 * @param string $method
	 * @param mixed  $route
	 * @param mixed  $action
	 * @return \EsTeh\Routing\RouteNaming
	 */
	private static function setRoute($method, $route, $action)
	{
		return app()->get("router")->collect($method, $route, $action);
	}

	public static function get($route, $action = null)
	{
		return self::setRoute("GET", $route, $action);
	}

	public static function post($route, $action = null)
	{
		return self::setRoute("POST", $route, $action);
	}

	public static function put($route, $action = null)
	{
		return self::setRoute("PUT", $route, $action);
	}

	public static function patch($route, $action = null)
	{
		return self::setRoute("PUT", $route, $action);
	}

	public static function openGroup($group)
	{
		return app()->get("router")->openGroup($group);
	}

	public static function closeGroup()
	{
		return app()->get("router")->closeGroup();
	}

	public static function group($group, $action)
	{
		self::openGroup($group);
		$r = app()->get("executor")->execute($action);
		self::closeGroup();
	}
}
