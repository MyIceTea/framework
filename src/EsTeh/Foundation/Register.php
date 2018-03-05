<?php

namespace EsTeh\Foundation;

use EsTeh\Hub\Singleton;
use EsTeh\Foundation\Application;
use EsTeh\Foundation\Support\ServiceProvider;

class Register
{
	use Singleton{
		Singleton::getInstance as getSelfInstance;
	}

	/**
	 * @var array
	 */
	private $singletonInstances = [];

	/**
	 * @var array
	 */
	private $serviceProviders = [];

	protected function __construct(Application $app)
	{
		$this->singleton("app", $app);
	}

	public static function init(Application $app)
	{
		return self::getSelfInstance($app);
	}

	public function singleton($name, $instance, $initParameters = [], $instantInit = true)
	{
		if (is_object($instance)) {
			$this->singletonInstances[$name] = $instance;
		} else {
			if (! $instantInit) {
				$this->singletonInstances[$name] = [
					$instance, $initParameters
				];
			} else {
				$this->singletonInstances[$name] = 
					new $instance(
						...$initParameters
					);
			}
		}
	}

	public function loadClassAliases()
	{
		$st = new AliasLoader(
			$this->getInstance("config")->get("app.aliases")
		);
		$st->load();
	}

	public function loadHelpers()
	{
		include ICETEA_VENDOR_DIR."/EsTeh/Support/helpers.php";
	}

	public function getInstance($name)
	{
		if (is_array($this->singletonInstances[$name])) {
			$this->singletonInstances[$name] = 
				new $this->singletonInstances[$name][0](
					...$this->singletonInstances[$name][1]
				);
		}
		return $this->singletonInstances[$name];
	}

	public function loadServiceProviders()
	{
		foreach ($this->getInstance("config")->get("app.providers") as $provider) {
			$this->initServiceProvider(new $provider);
		}
	}

	private function initServiceProvider(ServiceProvider $ins)
	{
		$ins->register();
		$ins->boot();
		$this->serviceProviders[get_class($ins)] = $ins;
	}

	public function getProvider($classname)
	{
		return $this->serviceProviders[$classname];
	}
}
