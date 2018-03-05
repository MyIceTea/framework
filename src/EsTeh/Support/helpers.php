<?php

if (! function_exists("app")) {
	/**
	 * @param mixed $param
	 * @return mixed
	 */
	function app($param = null)
	{
		return \EsTeh\Foundation\Register::getSelfInstance()->getInstance("app");
	}
}

if (! function_exists("env")) {
	/**
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	function env($key, $default = null)
	{
		return app()->getEnv($key, $default);
	}
}

if (! function_exists("ice_encrypt")) {
	/**
	 * @param string $str
	 * @param string $key
	 * @param bool	 $binarySafe
	 * @return string
	 */
	function ice_encrypt($str, $key, $binarySafe = true)
	{
		return \EsTeh\Security\Encryption\IceCrypt\IceCrypt::encrypt($str, $key, $binarySafe);
	}
}

if (! function_exists("ice_decrypt")) {
	/**
	 * @param string $str
	 * @param string $key
	 * @param bool	 $binarySafe
	 * @return string
	 */
	function ice_decrypt($str, $key, $binarySafe = true)
	{
		return \EsTeh\Security\Encryption\IceCrypt\IceCrypt::decrypt($str, $key, $binarySafe);
	}
}

if (! function_exists("rstr")) {
	/**
	 * @param int 	 $n
	 * @param string $e
	 * @return string
	 */
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
	/**
	 * @param mixed $parameters
	 * @return \EsTeh\Session\Session
	 */
	function session($parameters = null)
	{
		$s = app()->get("session");
		if (is_array($parameters)) {
			foreach ($parameters as $key => $value) {
				$s->set($key, $value);
			}
		}
		return $s;
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

if (! function_exists('ddd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed
     * @return void
     */
    function ddd(...$args)
    {
        foreach ($args as $x) {
            (new \EsTeh\Support\Debug\Dumper)->dump($x);
        }
    }
}

if (! function_exists("view")) {
	function view($name, $variables = [])
	{
		return \EsTeh\View\View::make($name, $variables);
	}
}

if (! function_exists("e")) {
	/**
	 * @param mixed $str
	 * @return string
	 */
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
	/**
	 * @param string $key
	 * @return mixed
	 */
	function config($key)
	{
		return app()->get("config")->get($key);
	}
}

if (! function_exists("response")) {
	/**
	 * @return \EsTeh\Support\Response
	 */
	function response()
	{
		return new \EsTeh\Support\Response;
	}
}


if (! function_exists("base_path")) {
	/**
	 * @param string $file
	 * @return string
	 */
	function base_path($file = "")
	{
		return app()->baseconfig[__FUNCTION__]."/".$file;
	}
}

if (! function_exists("config_path")) {
	/**
	 * @param string $file
	 * @return string
	 */
	function config_path($file = "")
	{
		return app()->baseconfig[__FUNCTION__]."/".$file;
	}
}

if (! function_exists("storage_path")) {
	/**
	 *  @param string $file
	 *  @return string
	 */
	function storage_path($file = "")
	{
		return app()->baseconfig[__FUNCTION__]."/".$file;
	}
}
