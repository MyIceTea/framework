<?php

namespace EsTeh\Http\Response;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionMethod;
use EsTeh\Contracts\Maker;
use EsTeh\Http\Response\Header;
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
		if ($this->response instanceof Closure) {
			$ref = new ReflectionMethod($this->response, '__invoke');
			$parameters = [];
			foreach ($ref->getParameters() as $parameter) {
				if ($parameter = $parameter->getClass()) {
					$parameters[] = new $parameter->name;
				}
			}
			$a = call_user_func($this->response, ...$parameters);
			if (null !== $a) {
				$this->maker($a);
			}
		} elseif (is_string($this->response)) {
			$controllerNamespace = RouteServiceProvider::getInstance()->getControllerNamespace();
			$class = explode('@', $this->response);
			if (count($class) !== 2) {
				throw new Exception("Error Processing Request", 1);
			}
			$reflectionClass = new ReflectionClass($classname = $controllerNamespace.'\\'.$class[0]);
			$controllerInstance = new $classname;
			$reflectionMethod = new ReflectionMethod($controllerInstance, $class[1]);
			call_user_func_array([$controllerInstance, $class[1]], []);
		} else {
			// unknown action
		}
	}

	private function maker(Maker $a)
	{

	}
}
