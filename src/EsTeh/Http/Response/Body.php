<?php

namespace EsTeh\Http\Response;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionMethod;
use EsTeh\Contracts\Maker;
use EsTeh\Http\Response\Header;
use EsTeh\Support\ObjectReflector;
use EsTeh\Contracts\Http\Response;
use App\Providers\RouteServiceProvider;

class Body implements Response
{
	private $response;

	public function __construct($response)
	{
		$this->response = $response;
	}

	public function buildBody(Header &$header)
	{
	}

	public function sendResponse()
	{
		if (is_string($this->response)) {
			$st = explode("@", $this->response);

			if (count($st) !== 2) {
				throw new InvalidActionException("Invalid route action [{$this->response}]", 1);
			}

			$rt = RouteServiceProvider::getInstance()->getControllerNamespace();
			
			$reflection = new ReflectionClass($class = $rt.'\\'.$st[0]);
			
			$parameters = [];
			if (method_exists($class, '__construct')) {
				$method = new ReflectionMethod($class, '__construct');
				foreach ($method->getParameters() as $param) {
					$param = $param->getClass();
					if (isset($param->name)) {
						$parameters[] = ObjectReflector::reflect($param->name);
					}
				}
			}

			$controller = new $class(...$parameters);
			$method = new ReflectionMethod($controller, $st[1]);
			$parameters = [];
			
			foreach ($method->getParameters() as $param) {
				$param = $param->getClass();
				if (isset($param->name)) {
					$parameters[] = ObjectReflector::reflect($param->name);
				}
			}

			$st = call_user_func_array([$controller, $st[1]], $parameters);
			if ($st instanceof Maker) {
				$this->make($st);
			}
		}
	}

	private function maker(Maker $st)
	{
	}
}
