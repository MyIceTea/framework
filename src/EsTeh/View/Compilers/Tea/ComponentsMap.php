<?php

namespace EsTeh\View\Compilers\Tea;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\View\Compilers\Tea
 * @license MIT
 */
class ComponentsMap
{
	public static $map = [
		"loops" => Components\Loops::class,
		"braces" => Components\Braces::class,
	];
}
