<?php

namespace EsTeh\Foundation;

class SingletonMap
{
	public static $map = [
		\EsTeh\Foundation\Application::class => "app",
		\EsTeh\Support\Config::class => "config",
		\EsTeh\Foundation\Executor::class => "executor",
		\App\Http\Kernel::class => "kernel",
		\EsTeh\Http\Request::class => "request",
		\EsTeh\Routing\Router::class => "router",
		\EsTeh\Session\Session::class => "session",
	];
}
