<?php

namespace EsTeh\Foundation\Http;

use ReflectionMethod;
use EsTeh\Support\ObjectReflector;
use EsTeh\Foundation\Http\NextMiddleware;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Foundation\Http
 * @license MIT
 */
class Middleware
{
	private $lastReturn;

	private $middlewares = [];

	public function __construct()
	{
		$this->middlewares = [
			\App\Http\Middleware\VerifyCsrfToken::class
		];
	}

	public function initMiddleware()
	{
		foreach ($this->middlewares as $key => $value) {
			$st = new $value();
			$reflection = new ReflectionMethod($st, "handle");
			$parameters = [];
			foreach($reflection->getParameters() as $param) {
				$parameters[] = ObjectReflector::reflect($param->getClass()->name);
			}
			$st = call_user_func_array([$st, "handle"], $parameters);
			if (! ($st instanceof NextMiddleware)) {
				break;
			}
		}
		$this->lastReturn = $st;
	}

	public function latestMiddlewareReturn()
	{
		return $this->lastReturn;
	}
}
