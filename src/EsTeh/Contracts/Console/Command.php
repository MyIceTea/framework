<?php

namespace EsTeh\Contracts\Console;

interface Command
{
	public function __construct($arguments);

	public function run();

	public function terminate();
}
