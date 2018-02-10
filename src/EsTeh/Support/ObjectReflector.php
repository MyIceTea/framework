<?php

namespace EsTeh\Support;

use EsTeh\Hub\Singleton;

class ObjectReflector
{
	use Singleton;

	protected function __construct()
	{
		$this->reflections = [
			"singleton" => [
				\EsTeh\Routing\Route::class,
				\EsTeh\Support\Config::class,
				\EsTeh\Foundation\Http\Route::class,
				\EsTeh\Foundation\Application::class,
				\EsTeh\Foundation\Http\Request::class,
				\App\Providers\RouteServiceProvider::class
			]
		];
	}

	public static function reflect($classname)
	{
		$ins = self::getInstance();
		if (array_search($classname, $ins->reflections["singleton"]) !== false) {
			return $classname::getInstance();
		} else {
			return new $classname();
		}
	}
}