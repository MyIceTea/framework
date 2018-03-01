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
			\EsTeh\Session\SessionHandler::batchSet($parameters);
		}
		return \EsTeh\Session\SessionHandler::getHandlerInstance();
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
		return \EsTeh\Http\CsrfFactory::getInstance();
	}
}

if (! function_exists('dd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed
     * @return void
     */
    function dd(...$args)
    {
        foreach ($args as $x) {
            (new \EsTeh\Support\Debug\Dumper)->dump($x);
        }

        die(1);
    }
}

if (! function_exists("view")) {
	function view($name, $variables = [])
	{
		return \EsTeh\View\View::make($name, $variables);
	}
}

if (! function_exists("e")) {
	function e($str)
	{
		if (is_string($str)) {
			return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
		}
		return $str;
	}
}

if (! function_exists("trans")) {
	function trans($name, $bind = [], $locale = null)
	{
		return \EsTeh\Support\Translation\Lang::get($name, $bind, $locale);
	}
}

if (! function_exists("config")) {
	function config($key)
	{
		$key = explode(".", $key, 2);
		if (count($key) === 1) {
			return \EsTeh\Support\Config::get($key[0]);
		}
		return \EsTeh\Support\Config::get($key[0])[$key[1]];
	}
}

if (! function_exists("response")) {
	function response()
	{
		return new \EsTeh\Support\Response;
	}
}