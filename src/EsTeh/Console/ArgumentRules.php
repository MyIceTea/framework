<?php

namespace EsTeh\Console;

class ArgumentRules
{
	public static $rules = [
		"commands" => [
			"" => [
				"strip" => [
					"-h" => ["state" => "help"],
				],
				"double_strip" => [
					"--help" => ["state" => "help"]
				]
			],
			"make:controller" => [
				"strip" => [
					"-f" => ["state" => "force"]
				],
				"double_strip" => [
					"--force" => ["state" => "force"]
				]
			],
			"make:model" => [
				"strip" => [
					"-f" => ["state" => "force"]
				],
				"double_strip" => [
					"--force" => ["state" => "force"]
				]
			],
			"make:middleware" => [
				"strip" => [
					"-f" => ["state" => "force"]
				],
				"double_strip" => [
					"--force" => ["state" => "force"]
				]
			],
			"serve" => [
				"strip" => [
					"-p" => ["state" => "port"],
					"-h" => ["state" => "host"]
				],
				"double_strip" => [
					"--port" => ["state" => "port"],
					"--host" => ["state" => "host"]
				]
			]
		]
	];
}