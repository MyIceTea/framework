<?php

namespace EsTeh\Console\Command\Make;

use Exception;
use EsTeh\Support\Config;
use InvalidArgumentException;
use EsTeh\Foundation\Application;
use App\Providers\RouteServiceProvider;
use EsTeh\Contracts\Console\Command as CommandContract;

class Controller implements CommandContract
{

	private $argv = [];

	public function __construct($arguments)
	{
		$this->argv = $arguments;
	}

	public function run()
	{
		$namespace = RouteServiceProvider::getInstance()->getControllerNamespace();
		$path = Application::$appPath['apppath'];
		$a = explode('\\', $namespace, 2);
		$b = explode('/', $path);
		$b = $b[count($b) - 1];
		if (ucfirst($b) === $a[0]) {
			$this->make($namespace, $path);
		} else {
			throw new Exception("App path and controller namespace does not match!", 1);
		}
	}

	private function make($namespace, $path)
	{
		if (count($this->argv) === 0) {
			$this->showHelps();
		} else {
			if (! isset($this->argv['name'])) {
				throw new InvalidArgumentException("Missing controller name", 1);
			}
			$r = str_replace('\\', '/', $this->argv['name'][0]['value']);
			$r = explode('/', $r);
			$name = $r[($c = count($r) - 1)];
			if (count($r) > 1) {
				unset($r[$c]);
				$namespace .= '\\'.implode('\\', $r);
			}
			$n = explode('\\', $namespace, 2);
			$path .= isset($n[1]) ? '/'.str_replace('\\', '/', $n[1]) : '';
			$this->makeDirRecursive($path);
			$this->makeFile(
				$path.'/'.$name.'.php', 
				__DIR__.'/../../stubs/controller.php.stub',
				$namespace,
				$name
			);
		}
	}

	private function makeFile($file, $stub, $namespace, $name, $controllerParent = 'App\\Http\\Controllers\\Controller')
	{
		file_put_contents($file, str_replace(
			[
				'{{ESTEH_VERSION}}',
				'{{DATE}}',
				'{{NAMESPACE}}',
				'{{NAME}}',
				'{{CONTROLLER_PARENT}}'
			],
			[
				ESTEH_VERSION,
				date('Y-m-d H:i:s'),
				$namespace,
				$name,
				$controllerParent
			], 
		file_get_contents($stub)));
	}

	private function makeDirRecursive($dir)
	{
		$d = explode('/', $dir);
		$p = '';
		foreach ($d as $key => $val) {
			if ($key === 0 && $val === "") {
				$p = "/";
			} elseif ($key !== 0) {
				$p .= $val.'/';
			}
			if (! is_dir($p)) {
				$t = mkdir($p);
				if (! $t) {
					throw new Exception("Cannot create directory [{$p}]", 1);
				}
			}
		}
	}

	private function showHelps()
	{
		echo 'This is helps'.PHP_EOL;
	}

	public function terminate()
	{

	}
}
