<?php

namespace EsTeh\Console;

class CommandRoutes
{
	public static $r = [
			'' => '',
			'make:controller' => \EsTeh\Console\Command\Make\Controller::class,
			'make:middleware' => \EsTeh\Console\Command\Make\Middleware::class,
			'make:model' => \EsTeh\Console\Command\Make\Model::class,
			'serve' => \EsTeh\Console\Command\Serve::class
	];
}