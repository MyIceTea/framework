<?php

namespace EsTeh\Foundation\Http\Middleware;

use EsTeh\Http\Request;
use EsTeh\Foundation\Http\NextMiddleware;

class VerifyCsrfToken
{
	protected $except = [];

	public function __construct(Request $request)
	{
		// dd($request);
	}

	final public function handle(NextMiddleware $next, Request $request)
	{
		return $next($request);
	}
}
