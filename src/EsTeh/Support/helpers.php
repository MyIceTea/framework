<?php

if (! function_exists("base_path")) {
	function base_path($file = "")
	{
		return \EsTeh\Foundation\Application::$appPath["basepath"]."/".$file;
	}
}

if (! function_exists("env")) {
	function env($key, $default = null)
	{
		return \EsTeh\Foundation\EnvirontmentVariables::get($key, $default);
	}
}

if (! function_exists("config_path")) {
	function config_path($file = "")
	{
		return \EsTeh\Foundation\Application::$appPath["configpath"]."/".$file;
	}
}

if (! function_exists("storage_path")) {
	function storage_path($file = "")
	{
		return \EsTeh\Foundation\Application::$appPath["storagepath"]."/".$file;
	}
}

if (! function_exists("ice_encrypt")) {
	function ice_encrypt($str, $key, $binarySafe = true)
	{
		return \EsTeh\Security\Encryption\IceCrypt\IceCrypt::encrypt($str, $key, $binarySafe);
	}
}

if (! function_exists("ice_decrypt")) {
	function ice_decrypt($str, $key, $binarySafe = true)
	{
		return \EsTeh\Security\Encryption\IceCrypt\IceCrypt::decrypt($str, $key, $binarySafe);
	}
}

if (! function_exists("rstr")) {
	function rstr($n = 32, $e = null)
	{
		if (! is_string($e)) {
			$e = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890___";
		}
		$rn = "";
		$ln = strlen($e) - 1;
		for ($i=0; $i < $n; $i++) { 
			$rn .= $e[rand(0, $ln)];
		}
		return $rn;
	}
}

if (! function_exists("session")) {
	function session($parameters = null)
	{
		if (is_array($parameters)) {
			return \EsTeh\Session\SessionHandler::batchSet($parameters);
		} else {
			return \EsTeh\Session\SessionHandler::getHandlerInstance();
		}
	}
}

if (! function_exists("app_key")) {
	function app_key()
	{
		return \EsTeh\Support\Config::get("app")["key"];
	}
}

if (! function_exists("csrf_token")) {
	function csrf_token()
	{
		return \EsTeh\Http\CsrfFactory::getToken();
	}
}

if (! function_exists("csrf_field")) {
	function csrf_field()
	{
		return "<input type=\"hidden\" name=\"_token\" value=\"".\EsTeh\Http\CsrfFactory::getToken()."\">";
	}
}
