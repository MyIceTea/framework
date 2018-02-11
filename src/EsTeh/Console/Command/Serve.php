<?php

namespace EsTeh\Console\Command;

use EsTeh\Console\Color as C;
use EsTeh\Contracts\Console\Command as CommandContract;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Console\Command
 * @license MIT
 */
class Serve implements CommandContract
{
	private $argv = [];

	public function __construct($arguments)
	{
		$this->argv = $arguments;
	}

	public function run()
	{
		$host = "127.0.0.1";
		$port = "8000";
		if (isset($this->argv["host"])) {
			if (isset($this->argv["name"])) {
				foreach ($this->argv["name"] as $key => $val) {
					if ($val["offset"] === $this->argv["host"]+1) {
						$host = $val["value"];
						unset($this->argv["name"][$key]);
						break;
					}
				}
			} else {
				throw new InvalidArgumentException("Undefined host");
			}
		}
		if (isset($this->argv["port"])) {
			if (isset($this->argv["name"])) {
				foreach ($this->argv["name"] as $key => $val) {
					if ($val["offset"] === $this->argv["port"]+1) {
						$port = $val["value"];
						break;
					}
				}
			} else {
				throw new InvalidArgumentException("Undefined port");
			}
		}
		if (! isset($this->argv["quiet"])) {
			echo C::clr("EsTeh development server started: ", "green")."<http://{$host}:{$port}>\n\n";
		}
		shell_exec(PHP_BINARY." -S {$host}:{$port} -t \"".BASEPATH."/public\"");
	}

	public function terminate()
	{
	}
}
