<?php

namespace EsTeh\Http;

use EsTeh\Contracts\Abilities\Renderable;
use EsTeh\Foundation\Http\NextMiddleware;

class HttpDispatcher
{
	private $action;

	public function __construct($action)
	{
		$this->action = $action;
	}

	private function runMiddleware()
	{
		$exe = app()->get("executor");
		$aliases = app()->get("kernel")->getMiddlewareAliases();
		if (isset($this->action["middleware"])) {
			foreach($this->action["middleware"] as $middleware) {
				if (is_array($middleware)) {
					foreach ($middleware as $middleware) {
						isset($aliases[$middleware]) and 
						$middleware = $aliases[$middleware];
						$next = $exe->execute($middleware."@handle");
						if (! ($next instanceof NextMiddleware)) {
							return $this->closeAction($next);
						}
					}
				} else {
					isset($aliases[$middleware]) and 
					$middleware = $aliases[$middleware];
					$next = $exe->execute($middleware."@handle");
					if (! ($next instanceof NextMiddleware)) {
						return $this->closeAction($next);
					}
				}
			}
		}
	}

	private function runAction()
	{
		$app = app();
		$exe = $app->get("executor");
		$this->closeAction(
			$exe->execute(
				$app->getProvider(
					\App\Providers\RouteServiceProvider::class
				)->getNamespace()."\\".
				$this->action["action"]
			)
		);
	}

	private function closeAction($exe)
	{
		if ($exe instanceof Renderable) {
			return $exe->render();
		}
	}

	public function exec()
	{
		$this->runMiddleware();
		$this->runAction();
	}
}
