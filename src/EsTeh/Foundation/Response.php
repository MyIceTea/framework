<?php

namespace EsTeh\Foundation;

use EsTeh\Http\HttpDispatcher;

class Response
{
	public function send()
	{
		$router = app()->get("router");
		$router = new HttpDispatcher(
			$router->handle()
		);
		$router->exec();
	}
}
