<?php

namespace EsTeh\Console;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Console
 * @license MIT
 */
class ArgumentRules
{
	public static $rules = [
		"commands" => [
			"" => [
				"strip" => [
					"-h" => ["state" => "help"],
					"-q" => ["state" => "quiet"]
				],
				"double_strip" => [
					"--help" => ["state" => "help"],
					"--quiet" => ["state" => "quiet"]
				]
			],
			"make:controller" => [
				"strip" => [
					"-f" => ["state" => "force"],
					"-q" => ["state" => "quiet"]
				],
				"double_strip" => [
					"--force" => ["state" => "force"],
					"--quiet" => ["state" => "quiet"]
				]
			],
			"make:model" => [
				"strip" => [
					"-f" => ["state" => "force"],
					"-q" => ["state" => "quiet"]
				],
				"double_strip" => [
					"--force" => ["state" => "force"],
					"--quiet" => ["state" => "quiet"]
				]
			],
			"make:middleware" => [
				"strip" => [
					"-f" => ["state" => "force"],
					"-q" => ["state" => "quiet"]
				],
				"double_strip" => [
					"--force" => ["state" => "force"],
					"--quiet" => ["state" => "quiet"]
				]
			],
			"serve" => [
				"strip" => [
					"-p" => ["state" => "port"],
					"-h" => ["state" => "host"],
					"-q" => ["state" => "quiet"]
				],
				"double_strip" => [
					"--port" => ["state" => "port"],
					"--host" => ["state" => "host"],
					"--quiet" => ["state" => "quiet"]
				]
			]
		]
	];
}