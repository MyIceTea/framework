<?php

namespace EsTeh\Foundation\Support\Providers;

use EsTeh\Routing\Route;
use EsTeh\Foundation\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
	protected $namespace = "App\\Http\\Controllers";

	protected $webRoutesFile;

	protected $apiRoutesFile;

	public function __construct()
	{
	}

	final public function loadRoutes()
	{
		$kernel = app()->get("kernel");

		$this->loadWebRoutes($kernel);
		$this->loadApiRoutes($kernel);
	}

	final protected function loadWebRoutes($kernel)
	{
		Route::openGroup(
			[
				"middleware" => $kernel->getWebMiddlewares()
			]
		);
		include $this->webRoutesFile;
		Route::closeGroup();
	}

	final protected function loadApiRoutes($kernel)
	{
		Route::openGroup(
			[
				"prefix" => "api",
				"middleware" => $kernel->getApiMiddlewares()
			]
		);
		include $this->apiRoutesFile;
		Route::closeGroup();
	}

	public function register()
	{
	}

	public function boot()
	{
	}

	public function getNamespace()
	{
		return $this->namespace;
	}
}
