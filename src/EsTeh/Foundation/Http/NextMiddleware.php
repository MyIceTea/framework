<?php

namespace EsTeh\Foundation\Http;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Foundation\Http
 * @license MIT
 */
class NextMiddleware
{
	public function __invoke()
	{
		return $this;
	}
}
