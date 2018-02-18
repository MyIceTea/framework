<?php

namespace EsTeh\View\Compilers;

abstract class CompilerComponent
{
	protected $strFile;

	/**
	 * Constructor.
	 *
	 * @param string $strFile
	 * @return void
	 */
	final public function __construct($strFile)
	{
		$this->strFile = $strFile;
		$this->compile();
	}

	abstract protected function compile();

	/**
	 * @return string
	 */
	final public function getCompiled()
	{
		return $this->strFile;
	}
}
