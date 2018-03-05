<?php

namespace EsTeh\Foundation;

use App\Http\Kernel;
use EsTeh\Http\Request;
use EsTeh\Routing\Router;
use EsTeh\Support\Config;
use EsTeh\Session\Session;
use EsTeh\Foundation\Capture;
use EsTeh\Foundation\Response;
use EsTeh\Foundation\Register;
use EsTeh\Foundation\Executor;

define("ICETEA_VERSION", "0.0.1");
define("ICETEA_VENDOR_DIR", realpath(__DIR__."/../.."));

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Foundation
 * @license MIT
 */
class Application
{
	/**
	 * @var array
	 */
	public $env = [];

	/**
	 * @var \EsTeh\Foundation\Register
	 */
	public $register;

	/**
	 * @var array
	 */
	public $baseconfig = [];

	/**
	 * Constructor.
	 *
	 * @param array $baseconfig
	 * @return void
	 */
	public function __construct($baseconfig)
	{
		$this->register = Register::init($this);
		$this->baseconfig = $baseconfig;
	}

	public function init()
	{		
		$this->register->singleton("config", Config::class, [$this->baseconfig["config_path"]]);
		$this->register->singleton("response", Response::class);
		$this->register->singleton("router", Router::class);
		$this->register->singleton("session", Session::class, [], false);
		$this->register->singleton("kernel", Kernel::class);
		$this->register->singleton("executor", Executor::class);
		$this->register->loadHelpers();
		$this->register->loadClassAliases();
		$this->register->singleton("router", Router::class);
		$this->register->loadServiceProviders();
	}

	public function getEnv($key, $default)
	{
		if (empty($this->env)) {
			$this->env = include $this->baseconfig["env_file"];
		}
		return array_key_exists($key, $this->env) ? $this->env[$key] : $default;
	}

	public function capture()
	{
		$this->register->singleton("request", Request::class);
	}

	public function sendResponse()
	{
		$this->get("response")->send();
	}

	public function terminate()
	{
	}

	public function get($name)
	{
		return $this->register->getInstance($name);
	}

	public function getProvider($classname)
	{
		return $this->register->getProvider($classname);
	}
}
