<?php

namespace EsTeh\Foundation\Http;

class NextMiddleware
{
	public function __invoke()
	{
		return $this;
	}
}
