<?php

namespace EsTeh\Contracts;

interface ServiceProvider
{
	public function boot();

	public function register();
}
