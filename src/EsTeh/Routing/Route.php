<?php

namespace EsTeh\Routing;

use EsTeh\Routing\RouteCollection;
use App\Providers\RouteServiceProvider;

/**
* 
*/
class Route
{
	/**
	 * @param string $uri
	 * @param mixed $action
	 */
	public static function get($uri, $action)
	{
		return RouteCollection::setRoute($uri, $action, "GET");
	}

	/**
	 * @param string $uri
	 * @param mixed $action
	 */
	public static function post($uri, $action)
	{
		return RouteCollection::setRoute($uri, $action, "POST");
	}

	/**
	 * @param string $uri
	 * @param mixed $action
	 */
	public static function delete($uri, $action)
	{
		return RouteCollection::setRoute($uri, $action, "DELETE");
	}

	/**
	 * @param string $uri
	 * @param mixed $action
	 */
	public static function put($uri, $action)
	{
		return RouteCollection::setRoute($uri, $action, "PUT");
	}

	/**
	 * @param string $uri
	 * @param mixed $action
	 */
	public static function patch($uri, $action)
	{
		return RouteCollection::setRoute($uri, $action, "PATCH");
	}

	/**
	 * @param string $uri
	 * @param mixed $action
	 */
	public static function any($uri, $action)
	{
		return RouteCollection::setRoute($uri, $action, "*");
	}

	/**
	 * @param array $uri
	 * @param mixed $action
	 */
	public static function group($uri, $action)
	{
		RouteCollection::group($uri["prefix"]);
		if (is_string($action)) {
			$action = explode("@", $action);
			if (count($action) !== 2) {
				throw new \Exception("Error Processing Request", 1);
			}
			$class = RouteServiceProvider::getInstance()->getControllerNamespace()."\\".$action[0];
			call_user_func_array([$class, $action[1]], []);
		} else {
			$action();
		}
		RouteCollection::closeGroup();
		return true;
	}
}
