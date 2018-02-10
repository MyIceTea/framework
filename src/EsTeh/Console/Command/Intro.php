<?php

namespace EsTeh\Console\Command;

use EsTeh\Console\Color as C;
use EsTeh\Contracts\Console\Command as CommandContract;

class Intro implements CommandContract
{
	private $argv = [];

	public function __construct($arguments)
	{
		$this->argv = $arguments;
	}

	public function run()
	{
echo 
"
EsTeh Framework ".C::clr(ESTEH_VERSION, "green")."

".C::clr("Usage:", "brown")."
  command [options] [arguments]

".C::clr("Options:", "brown")."
  ".C::clr("-h, --help", "green")."		Display this help message
  ".C::clr("-q, --quite", "green")."		Do not output any message

".C::clr("Available commands:", "brown")."
  ".C::clr("serve", "green")."			Serve the application on the PHP development server
 ".C::clr("make", "brown")."
  ".C::clr("make:controller", "green")."	Create a new controller class
  ".C::clr("make:model", "green")."		Create a new model class
 ".C::clr("view", "brown")."
  ".C::clr("view:clear", "green")."		Clear all compiled view files
";
	}

	public function terminate()
	{

	}
}
