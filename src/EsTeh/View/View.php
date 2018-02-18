<?php

namespace EsTeh\View;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\View
 * @license MIT
 */
class View
{
	public function __construct()
	{

	}

	public function __toString()
	{
		return $this->render();
	}
}
