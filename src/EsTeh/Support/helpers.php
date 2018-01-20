<?php

if (! function_exists('base_path')) {
	function base_path($file = "")
	{
		return \EsTeh\Foundation\Application::$appPath['basepath'].'/'.$file;
	}
}

if (! function_exists('env')) {
	function env($key, $default = null)
	{
		return \EsTeh\Foundation\EnvirontmentVariables::get($key, $default);
	}
}

if (! function_exists('config_path')) {
	function config_path($file = "")
	{
		return \EsTeh\Foundation\Application::$appPath['configpath'].'/'.$file;
	}
}
