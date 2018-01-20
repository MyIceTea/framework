<?php

namespace EsTeh\Http\Response;

use Closure;
use ReflectionClass;
use ReflectionMethod;
use EsTeh\Contracts\Maker;
use EsTeh\Http\Response\Header;
use EsTeh\Contracts\Http\Response;

class Body implements Response
{
	private $response;

	public function __construct($response)
	{
		$this->response = $response;
	}

	public function buildBody(Header &$header)
	{
		$header->aaa = 123;
		var_dump($header->aaa);
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
		}
	}

	private function maker(Maker $a)
	{

	}
}
