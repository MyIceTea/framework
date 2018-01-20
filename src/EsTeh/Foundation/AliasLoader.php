<?php

namespace EsTeh\Foundation;

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
