<?php

namespace EsTeh\Console\Command\Make;

use Exception;
use EsTeh\Console\Color;
use EsTeh\Support\Config;
use InvalidArgumentException;
use EsTeh\Foundation\Application;
use App\Providers\RouteServiceProvider;
use EsTeh\Contracts\Console\Command as CommandContract;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Console\Command\Make
 * @license MIT
 */
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
		$path = Application::$appPath["apppath"];
		$a = explode("\\", $namespace, 2);
		$b = explode("/", $path);
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
			if (! isset($this->argv["name"])) {
				throw new InvalidArgumentException("Missing controller name", 1);
			}
			$r = str_replace("\\", "/", $this->argv["name"][0]["value"]);
			$r = explode("/", $r);
			foreach ($r as $rr) {
				if (preg_match("/[^\/\w\d\_]/", $rr)) {
					throw new InvalidArgumentException("Invalid controller namespace [{$rr}]", 1);
				}
			}
			$name = $r[($c = count($r) - 1)];
			if (count($r) > 1) {
				unset($r[$c]);
				$namespace .= "\\".implode("\\", $r);
			}
			$n = explode("\\", $namespace, 2);
			$path .= isset($n[1]) ? "/".str_replace("\\", "/", $n[1]) : "";
			$this->makeDirRecursive($path);
			$this->makeFile(
				$path."/".$name.".php", 
				__DIR__."/../../stubs/controller.php.stub",
				$namespace,
				$name,
				isset($this->argv["force"])
			);
		}
	}

	private function makeFile($file, $stub, $namespace, $name, $forced = false, $controllerParent = "App\\Http\\Controllers\\Controller")
	{
		if (file_exists($file) && !$forced) {
			print Color::clr("Controller already exists!", "grey", "red");
		} else {
			$w = file_put_contents($file, str_replace(
			[
				"{{ESTEH_VERSION}}",
				"{{DATE}}",
				"{{NAMESPACE}}",
				"{{NAME}}",
				"{{CONTROLLER_PARENT}}"
			],
			[
				ESTEH_VERSION,
				date("Y-m-d H:i:s"),
				$namespace,
				$name,
				$controllerParent
			], 
			file_get_contents($stub)));
			if ($w) {
				print Color::clr("Controller created successfully.", "green");
			} else {
				print Color::clr("Cannot create controller", "grey", "red");
			}
		}
	}

	private function makeDirRecursive($dir)
	{
		$d = explode("/", $dir);
		$p = "";
		foreach ($d as $key => $val) {
			if ($key === 0 && $val === "") {
				$p = "/";
			} elseif ($key !== 0) {
				$p .= $val."/";
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
		echo "This is helps".PHP_EOL;
	}

	public function terminate()
	{
	}
}
