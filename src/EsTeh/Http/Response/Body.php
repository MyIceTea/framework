<?php

namespace EsTeh\Http\Response;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionMethod;
use EsTeh\Http\Response\Header;
use EsTeh\Support\ObjectReflector;
use EsTeh\Contracts\Http\Response;
use App\Providers\RouteServiceProvider;
use EsTeh\Contracts\Abilities\Renderable;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Http\Response
 * @license MIT
 */
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
		$st = null;
		if (is_string($this->response)) {
			$st = explode("@", $this->response);

			if (count($st) !== 2) {
				throw new InvalidActionException("Invalid route action [{$this->response}]", 1);
			}

			$rt = RouteServiceProvider::getInstance()->getControllerNamespace();
			
			$reflection = new ReflectionClass($class = $rt."\\".$st[0]);
			
			$parameters = [];
			if (method_exists($class, "__construct")) {
				$method = new ReflectionMethod($class, "__construct");
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
			
		} elseif (is_callable($this->response)) {
			$reflection = new ReflectionClass($this->response);
			if ($reflection->hasMethod("__invoke")) {
				$reflection = new ReflectionMethod($this->response, "__invoke");
				$parameters = [];
				foreach ($reflection->getParameters() as $param) {
					$param = $param->getClass();
					if (isset($param->name)) {
						$parameters[] = ObjectReflector::reflect($param->name);
					}
				}
				$st = call_user_func_array([$this->response, "__invoke"], $parameters);
			}
		}

		if ($st instanceof Renderable) {
			return $st->render();
		}

		if ($this->stringable($st)) {
			return print $st;
		}
	}

	private function render(Maker $st)
	{
		$st->render();
	}

	private function stringable($st)
	{
		return is_int($st) || is_string($st) || is_callable([$st, "__toString"]);
	}
}
