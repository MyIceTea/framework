<?php

namespace EsTeh\Foundation;

use EsTeh\Hub\Singleton;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 * @license MIT
 * @version 0.0.1
 */
class Application
{
	use Singleton;

	/**
	 * @var array
	 */
	private $instances = [];

	/**
	 * @var array
	 */
	public static $pathinfo;

	/**
	 * @var array
	 */
	private $services = [];

	/**
	 * @var array
	 */
	public $env = [];

	/**
	 * Constructor.
	 *
	 * @param array $pathinfo
	 */
	public function __construct($pathinfo)
	{
		self::$pathinfo = $pathinfo;
		self::$instance = $this;
	}

	/**
	 * Init app
	 */
	public function init()
	{
		$this->loadHelpers();
	}

	private function loadHelpers()
	{
		if (function_exists('Composer\Autoload\includeFile')) {
			\Composer\Autoload\includeFile(__DIR__.'/../Support/helpers.php');
		} else {
			function includeFile($file)
			{
				require $file;
			}
			includeFile(__DIR__.'/../Support/helpers.php');
		}
	}

	/**
	 * Add provider.
	 */
	public function addProvider()
	{

	}

	/**
	 * @param array $instances
	 */
	public function capture($instances)
	{
		$this->instances = $instances;
	}

	/**
	 * @param array $env
	 */
	public static function setEnv($env)
	{
		self::getInstance()->env = $env;
	}
}
