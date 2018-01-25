<?php

namespace EsTeh\Foundation\Support\Providers;

use EsTeh\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
	/**
	 * @var string
	 */
	protected $namespace;

	public function getControllerNamespace()
	{
		return $this->namespace;
	}
}
