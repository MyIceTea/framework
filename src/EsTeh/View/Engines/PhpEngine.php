<?php

namespace EsTeh\View\Engines;

use Error;
use EsTeh\Exception\View\ErrorView;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\View\Engines
 * @license MIT
 */
class PhpEngine
{
	private $compiler;

	public function __construct($compiler)
	{
		$this->compiler = $compiler;
	}

	public function run()
	{
		ob_start();

		extract($this->compiler->variables);

		include $this->compiler->getFile();

		return ob_get_clean();
	}

	public function render()
	{
		return $this->run();
	}
}
