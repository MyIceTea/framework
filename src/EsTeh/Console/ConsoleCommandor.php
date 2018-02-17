<?php

namespace EsTeh\Console;

use Exception;
use EsTeh\Console\Color;
use EsTeh\Console\ArgvInput;
use InvalidArgumentException;
use EsTeh\Console\ArgumentRules;
use EsTeh\Console\CommandRoutes;
use EsTeh\Contracts\Console\Command as CommandContract;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Console
 * @license MIT
 */
class ConsoleCommandor
{
	private $argv;

	public function __construct()
	{
		$this->argv = new ArgvInput();
	}

	public function run()
	{
		try {
			$command = "";
			$args = [];
			foreach ($this->argv->toArray() as $key => $val) {
				if ($key === 0) {
					if (substr($val, 0, 1) === "-") {
						$command = "";
						if (substr($val, 0, 2) === "--") {
						if (isset(ArgumentRules::$rules["commands"][$command]["double_strip"][$val]["state"])) {
								$args[ArgumentRules::$rules["commands"][$command]["double_strip"][$val]["state"]] = $key;
							} else {
								throw new InvalidArgumentException("Invalid argument [{$val}]", 1);
							}	
						} elseif (substr($val, 0, 1) === "-") {
							if (isset(ArgumentRules::$rules["commands"][$command]["strip"][$val]["state"])) {
								$args[ArgumentRules::$rules["commands"][$command]["strip"][$val]["state"]] = $key;
							} else {
								throw new InvalidArgumentException("Invalid argument [{$val}]", 1);
							}
						}
						continue;
					} elseif (! isset(CommandRoutes::$r[$val])) {
						throw new InvalidArgumentException("Invalid command [{$val}]", 1);
					}
					$command = $val;
				} else {
					if (substr($val, 0, 2) === "--") {
						if (isset(ArgumentRules::$rules["commands"][$command]["double_strip"][$val]["state"])) {
							$args[ArgumentRules::$rules["commands"][$command]["double_strip"][$val]["state"]] = $key;
						} else {
							throw new InvalidArgumentException("Invalid argument [{$val}]", 1);
						}	
					} elseif (substr($val, 0, 1) === "-") {
						if (isset(ArgumentRules::$rules["commands"][$command]["strip"][$val]["state"])) {
							$args[ArgumentRules::$rules["commands"][$command]["strip"][$val]["state"]] = $key;
						} else {
							throw new InvalidArgumentException("Invalid argument [{$val}]", 1);
						}
					} else {
						$args["name"][] = ["offset" => $key, "value" => $val];
					}
				}
			}
			$app = new CommandRoutes::$r[$command]($args);
			$this->runApp($app);
		} catch (Exception $e) {
			echo "\n".Color::clr("Error [".get_class($e)."]: ".$e->getMessage()."\n\n", "", "red");
		}
	}

	private function runApp(CommandContract $app)
	{
		try {
			$app->run();
			$app->terminate();	
		} catch (Exception $e) {
			echo "\n".Color::clr("Error [".get_class($e)."]: ".$e->getMessage()."\n\n", "", "red");
		}
	}
}
