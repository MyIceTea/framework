<?php

namespace EsTeh\Routing;

use EsTeh\Routing\RouteCollection;

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
		return RouteCollection::setRoute($uri, $action, 'GET');
	}

	/**
	 * @param string $uri
	 * @param mixed $action
	 */
	public static function post($uri, $action)
	{
		return RouteCollection::setRoute($uri, $action, 'POST');
	}

	/**
	 * @param string $uri
	 * @param mixed $action
	 */
	public static function delete($uri, $action)
	{
		return RouteCollection::setRoute($uri, $action, 'DELETE');
	}

	/**
	 * @param string $uri
	 * @param mixed $action
	 */
	public static function put($uri, $action)
	{
		return RouteCollection::setRoute($uri, $action, 'PUT');
	}

	/**
	 * @param string $uri
	 * @param mixed $action
	 */
	public static function patch($uri, $action)
	{
		return RouteCollection::setRoute($uri, $action, 'PATCH');
	}

	/**
	 * @param string $uri
	 * @param mixed $action
	 */
	public static function any($uri, $action)
	{
		return RouteCollection::setRoute($uri, $action, '*');
	}	
}
