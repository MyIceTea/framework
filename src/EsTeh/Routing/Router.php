<?php

namespace EsTeh\Routing;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Routing
 * @license MIT
 */
class Router
{
	public static function capture()
	{
		return isset($_SERVER["PATH_INFO"]) ? $_SERVER["PATH_INFO"] : "/";
	}

	public static function loadWebroutes($file)
	{
		require $file;
	}
}
