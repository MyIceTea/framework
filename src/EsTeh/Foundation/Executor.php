<?php

namespace EsTeh\Foundation;

use Closure;
use ReflectionClass;
use ReflectionMethod;

class Executor
{
	public function execute($callable)
	{
		if (is_string($callable)) {
			$callable = explode("@", $callable, 2);
			if (count($callable) === 2) {
				$reflect = new ReflectionClass($callable[0]);
				
				$c = $reflect->getConstructor();

				if ($c instanceof ReflectionMethod) {
					$parameters = [];
					foreach($c->getParameters() as $param) {
						if ($param = $param->getClass()) {
							$parameters[] = $this->objectReflect($param->name);
						} else {
							$parameters[] = 0;
						}
					}
				}
				$d = new $callable[0](...$parameters);

				$c = new ReflectionMethod($d, $callable[1]);
				$parameters = [];
				foreach($c->getParameters() as $param) {
					if ($param = $param->getClass()) {
						$parameters[] = $this->objectReflect($param->name);
					} else {
						$parameters[] = 0;
					}
				}
				
				return call_user_func_array([$d, $callable[1]], $parameters);
			}
		} elseif ($callable instanceof Closure) {
			$c = new ReflectionMethod($callable, "__invoke");
			$parameters = [];
			foreach($c->getParameters() as $param) {
				if ($param = $param->getClass()) {
					$parameters[] = $this->objectReflect($param->name);
				} else {
					$parameters[] = 0;
				}
			}
		} else {
			$parameters = [];
		}
		return $callable(...$parameters);
	}

	private function objectReflect($param)
	{
		if ($singleton = $this->isSingleton($param)) {
			return $singleton;
		}
		return new $param;
	}

	private function isSingleton($class)
	{
		if (isset(SingletonMap::$map[$class])) {
			return app()->get(SingletonMap::$map[$class]);
		}
	}
}