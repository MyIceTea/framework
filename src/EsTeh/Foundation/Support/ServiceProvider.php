<?php

namespace EsTeh\Foundation\Support;

abstract class ServiceProvider
{
	abstract public function register();
	
	abstract public function boot();
}
