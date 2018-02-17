<?php

namespace EsTeh\Foundation;

define("ESTEH_VERSION", "0.0.1");

use Whoops\Run;
use EsTeh\Hub\Singleton;
use EsTeh\Support\Config;
use EsTeh\Foundation\HttpAction;
use EsTeh\Foundation\AliasLoader;
use Whoops\Handler\PrettyPageHandler;
use EsTeh\Exception\ApplicationException;
use EsTeh\Contracts\Response as ResponseContract;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Foundation
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
	public static $appPath;

	/**
	 * @var array
	 */
	private $services = [];

	/**
	 * @var array
	 */
	public $env = [];

	/**
	 * @var bool
	 */
	private $shouldBeSendResponse = false;

	/**
	 * @var array
	 */
	private $responses = [];

	/**
	 * @var array
	 */
	private $providers = [];

	/**
	 * Constructor.
	 *
	 * @param array $pathinfo
	 */
	public function __construct($appPath)
	{
		$this->registerErrorHandler();
		self::$appPath = $appPath;
		self::$__instances[self::class] = $this;
	}

	/**
	 * Init app
	 */
	public function init()
	{
		$this->loadHelpers();
		$this->loadAlias();
	}

	private function loadHelpers()
	{
		if (function_exists("Composer\Autoload\includeFile")) {
			\Composer\Autoload\includeFile(__DIR__."/../Support/helpers.php");
		} else {
			function includeFile($file)
			{
				require $file;
			}
			includeFile(__DIR__."/../Support/helpers.php");
		}
	}

	private function loadAlias()
	{
		$st = new AliasLoader(Config::get("app.aliases"));
		$st->load();
	}

	public function prepareAction()
	{
		$this->responses = HttpAction::action($this->providers);
		$this->shouldBeSendResponse = count($this->responses);
	}

	/**
	 * Add provider.
	 */
	public function addProvider($providers)
	{
		$this->providers = $providers;
	}

	/**
	 * @param array $instances
	 */
	public function capture($instances)
	{
		foreach ($instances as $val) {
			if (! $val) {
				throw new ApplicationException("Error Processing Request");
			}
		}
	}

	/**
	 * @param array $env
	 */
	public static function setEnv($env)
	{
		self::getInstance()->env = $env;
	}

	/**
	 * Send response.
	 */
	public function sendResponse()
	{
		if ($this->shouldBeSendResponse) {
			foreach($this->responses as $response) {
				$this->privateSendResponse($response);
			}
		}
	}

	private function privateSendResponse(ResponseContract $res)
	{
		$res->sendResponse();
	}

	public function terminate()
	{
	}

	private function registerErrorHandler()
	{
		$whoops = new Run;
		$whoops->pushHandler(new PrettyPageHandler);
		$whoops->register();
	}
}
