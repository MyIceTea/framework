<?php

namespace EsTeh\Foundation;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Foundation
 * @license MIT
 */
class AliasLoader
{
	private $aliases;

	public function __construct($aliases)
	{
		$this->aliases = $aliases;
	}

	public function load()
	{
		spl_autoload_register(function ($class) {
			if (isset($this->aliases[$class])) {
				class_alias($this->aliases[$class], $class, true);
			}
		});
	}
}
