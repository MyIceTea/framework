<?php

namespace EsTeh\View\Engines;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\View\Engines
 * @license MIT
 */
class PhpEngine
{
	public function __construct($compiler)
	{
		$this->compiler = $compiler;
	}

	public function run()
	{
		ob_start();

		include $this->compiler->getFile();

		return ob_get_clean();
	}

	public function render()
	{
		return $this->run();
	}
}
