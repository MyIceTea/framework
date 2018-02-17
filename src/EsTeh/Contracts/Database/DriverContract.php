<?php

namespace EsTeh\Contracts\Database;

interface DriverContract
{
	public function getPdo();

	public function execMethod($method, $parameters);
}
