<?php

namespace EsTeh\Routing;

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
