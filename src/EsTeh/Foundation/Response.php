<?php

namespace EsTeh\Foundation;

class Response
{
	public function send()
	{
		$router = app()->get("router");
		$action = $router->handle();
		dd($action);
	}
}
