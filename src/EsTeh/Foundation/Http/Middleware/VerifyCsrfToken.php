<?php

namespace EsTeh\Foundation\Http\Middleware;

use EsTeh\Http\Request;
use EsTeh\Http\CsrfFactory;
use EsTeh\Foundation\Http\NextMiddleware;
use EsTeh\Exception\TokenMismatchException;

class VerifyCsrfToken
{
	protected $token;

	protected $config = [];

	protected $app;

	protected $isActive;

	protected $except = [];

	protected $isGetMethod;

	public function __construct(Request $request)
	{
		$this->app = app();
		$this->config = config("security.csrf");
		$this->isActive = $this->config["protection"] and 
		($this->app->register->singleton("csrf_middleware", $this) xor
		$this->isGetMethod = $_SERVER["REQUEST_METHOD"] === "GET");
		$this->app->register->singleton("csrf_factory", CsrfFactory::class, 
			[
				$this->config,
				(! isset($_COOKIE[$this->config["cookie_name"]])) &&
				$this->isGetMethod
			]
		);
	}

	public function __toString()
	{
		return "<input type=\"hidden\" name=\"_token\" value=\"{$this->token}\"/>";
	}

	public function getToken()
	{
		return $this->token;
	}

	final protected function finalHandler(NextMiddleware $next, Request $request)
	{	
		$cookie = json_decode(ice_decrypt(
			$_COOKIE[$this->config["cookie_name"]], config("app.key")
		), true);
		$factory = $this->app->get("csrf_factory");
		if (
			isset($cookie["token"], $cookie["expired"]) &&
			$cookie["expired"] >= time()
		) {
			if ($this->isGetMethod) {
				$factory->reinitialize();
				$factory = $factory->getToken();
			} else {
				if (
					! isset($_POST["_token"]) ||
					$_POST["_token"] !== $cookie["token"]
				) {
					throw new TokenMismatchException("Token Mismatch");
				} else {
					$factory = $cookie["token"];
				}
			}
		} else {
			if ($this->isGetMethod) {
				$factory->reinitialize();
				$factory = $factory->getToken();
			} else {
				throw new TokenMismatchException("Token Mismatch");
			}
		}
		$this->token = $factory;
		return $next($request);
	}

	public function handle(NextMiddleware $next, Request $request)
	{
		return $this->finalHandler($next, $request);
	}
}
