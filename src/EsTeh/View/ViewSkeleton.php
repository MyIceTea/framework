<?php

namespace EsTeh\View;

use EsTeh\Support\Config;
use EsTeh\View\Engines\PhpEngine;
use EsTeh\Exception\View\ViewException;
use EsTeh\View\Compilers\Tea\TeaCompiler;
use EsTeh\Contracts\Abilities\Stringable;
use EsTeh\Contracts\Abilities\Renderable;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\View
 * @license MIT
 */
class ViewSkeleton implements Stringable, Renderable
{
	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $file;

	/**
	 * @var \EsTeh\View\Compilers\TeaCompiler
	 */
	private $compiler;

	/**
	 * @var bool
	 */
	private $dontCompile = false;

	/**
	 * @var array
	 */
	private $variables = [];

	/**
	 * @param string $name
	 * @param array $variables
	 */
	public function __construct($name, $variables = [])
	{
		$this->name = str_replace(".", "/", $name);
		$this->variables = $variables;
		$this->getTemplateFile();
		$this->compileView();
		$this->buildView();
	}

	private function getTemplateFile()
	{
		$p = config("app.views_path");
		if (file_exists($f = "{$p}/{$this->name}.tea.php")) {
			$this->file = $f;
			return;
		} elseif (file_exists($f = "{$p}/{$this->name}.blade.php")) {
			$this->file = $f;
			return;
		} elseif (file_exists($f = "{$p}/{$this->name}.php")) {
			$this->file = $f;
			$this->dontCompile = true;
			return;
		}
		throw new ViewException("View [{$this->name}] does not exist", 1);
	}

	private function compileView()
	{
		$this->compiler = new TeaCompiler($this->file, storage_path("framework/views"), $this->dontCompile, $this->variables);
		if ($this->compiler->isCompiledFileHasBeenExpired()) {
			$this->compiler->compile();
		}
	}

	private function buildView()
	{
		$this->engine = new PhpEngine($this->compiler);
		$this->compiler = $this->engine->render();
	}

	public function __toString()
	{
		return $this->compiler;
	}

	public function render()
	{
		header("Content-Type:text/html");
		echo $this;
	}
}
