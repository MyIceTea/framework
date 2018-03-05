<?php

namespace EsTeh\Foundation\Http;

class NextMiddleware
{
	public $next;

	public function __invoke($next)
	{
		$this->next = $next;
		return $this;
	}
}
