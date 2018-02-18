<?php

namespace EsTeh\Support\Translation;

class Lang
{
	public function get($name, $bind = [], $locale = null)
	{
		$name = explode(".", $name);
		$s = include base_path("resources/lang/en/".$name[0].".php");
		return $s[$name[1]];
	}
}
