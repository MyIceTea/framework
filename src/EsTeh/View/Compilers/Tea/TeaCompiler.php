<?php

namespace EsTeh\View\Compilers\Tea;

use EsTeh\View\Compilers\Tea\ComponentsMap;
use EsTeh\View\Compilers\CompilerComponent;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\View\Compilers\Tea
 * @license MIT
 */
class TeaCompiler
{	
	/**
	 * @var string
	 */
	private $file;

	/**
	 * @var string
	 */
	private $compiledPath;

	/**
	 * @var string
	 */
	private $compiledFile;

	/**
	 * @var bool
	 */
	private $dontCompile;

	/**
	 * Constructor.
	 *
	 * @param string $file
	 * @param string $compiledPath
	 * @param bool   $dontCompile
	 * @return void
	 */
	public function __construct($file, $compiledPath, $dontCompile = false)
	{
		$this->file = $file;
		$this->compiledPath = $compiledPath;
		$this->dontCompile = $dontCompile;
		$this->getFileInfo();
	}

	private function getFileInfo()
	{
		$this->compiledFile = $this->compiledPath."/".sha1($this->file).".php";
	}

	public function isCompiledFileHasBeenExpired()
	{
		return 1;
		return !(file_exists($this->compiledFile) &&
			filemtime($this->file) < filemtime($this->compiledFile));
	}

	/**
	 * @return bool
	 */
	public function compile()
	{
		$strFile = file_get_contents($this->file);
		if (! $this->dontCompile) {
			foreach (ComponentsMap::$map as $key => $val) {
				$this->make(new $val($strFile), $strFile);
			}
		}
		file_put_contents($this->compiledFile, $strFile);
	}

	private function make(CompilerComponent $val, &$strFile)
	{
		$strFile = $val->getCompiled();
	}

	public function getFile()
	{
		return $this->compiledFile;
	}
}
