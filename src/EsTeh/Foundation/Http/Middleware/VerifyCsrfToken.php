<?php

namespace EsTeh\Foundation\Http\Middleware;

use EsTeh\Hub\Singleton;
use EsTeh\Support\Config;
use EsTeh\Http\CsrfFactory;
use EsTeh\Foundation\Http\Route;
use EsTeh\Foundation\Http\Request;
use EsTeh\Foundation\Http\NextMiddleware;
use EsTeh\Exception\TokenMismatchException;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Foundation\Http\Middleware
 * @license MIT
 */
class VerifyCsrfToken
{
	protected $except = [];

	public function handle(NextMiddleware $next, Request $request, Route $route)
	{
		$config = Config::get("security")["csrf"];
		if ($_SERVER["REQUEST_METHOD"] === "GET") {
			CsrfFactory::initCsrfCookie($config);
		} else {
			$route = $route->getCurrentRoute();
			foreach ($this->except as $val) {
				if (preg_match($val, $route)) {
					return $next($request);
				}
			}
			if (isset($_COOKIE[$config["cookie_name"]])) {
				$st = ice_decrypt($_COOKIE[$config["cookie_name"]], app_key());
				$st = json_decode($st, true);
				if (
					!(is_array($st) and isset($st["token"], $st["expired"]) and $st["expired"] > time() and isset($_POST["_token"]) && $_POST["_token"] === $st["token"])
				) {
					throw new TokenMismatchException("Token mismatch");
				}
			}
		}
		return $next($request);
	}
}
