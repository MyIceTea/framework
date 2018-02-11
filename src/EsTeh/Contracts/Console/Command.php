<?php

namespace EsTeh\Contracts\Console;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Contracts\Console
 * @license MIT
 */
interface Command
{
	public function __construct($arguments);

	public function run();

	public function terminate();
}
