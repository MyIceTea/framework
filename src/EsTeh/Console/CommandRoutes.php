<?php

namespace EsTeh\Console;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Console
 * @license MIT
 */
class CommandRoutes
{
	public static $r = [
			"" => \EsTeh\Console\Command\Intro::class,
			"make:controller" => \EsTeh\Console\Command\Make\Controller::class,
			"make:middleware" => \EsTeh\Console\Command\Make\Middleware::class,
			"make:model" => \EsTeh\Console\Command\Make\Model::class,
			"serve" => \EsTeh\Console\Command\Serve::class
	];
}