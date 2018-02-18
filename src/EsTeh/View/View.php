<?php

namespace EsTeh\View;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\View
 * @license MIT
 */
class View
{
	public static function make($name, $variables = [])
	{
		return new ViewSkeleton($name, $variables);
	}
}
