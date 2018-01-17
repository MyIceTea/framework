<?php

if (! function_exists('base_path')) {
	function base_path($file = "")
	{
		return \EsTeh\Foundation\Application::$pathinfo['basepath'].'/'.$file;
	}
}

if (! function_exists('env')) {
	function env($key, $default = null)
	{
		return \EsTeh\Foundation\EnvirontmentVariables::get($key, $default);
	}
}
