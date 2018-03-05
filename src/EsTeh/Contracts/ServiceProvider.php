<?php

namespace EsTeh\Contracts;

interface ServiceProvider
{
	public function register();

	public function boot();
}
