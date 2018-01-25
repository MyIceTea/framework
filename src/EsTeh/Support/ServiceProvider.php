<?php

namespace EsTeh\Support;

use EsTeh\Hub\Singleton;
use EsTeh\Contracts\ServiceProvider as ServiceProviderContract;

class ServiceProvider implements ServiceProviderContract
{
	use Singleton;

	/**
	 * Constructor.
	 */
	public function __construct()
	{
	}

	/**
	 * Boot.
	 */
	public function boot()
	{
	}

	/**
	 * Register.
	 */
	public function register()
	{
	}
}
