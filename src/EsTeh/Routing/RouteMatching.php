<?php

namespace EsTeh\Routing;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Routing
 * @license MIT
 */
class RouteMatching
{
	/**
	 * @var array
	 */
	private $uri = [];

	/**
	 * @var mixed
	 */
	private $action;

	/**
	 * @var array
	 */
	private $routes = [];

	/**
	 * Constructor.
	 *
	 * @param array $routes
	 * @return void
	 */
	public function __construct($routes)
	{
		$this->generateUri();
		$this->routes = $routes;
		$this->match();
	}

	private function generateUri()
	{
		if (isset($_SERVER["PATH_INFO"])) {
			$this->uri = $_SERVER["PATH_INFO"];
			do {
				$this->uri = str_replace("//", "/", $this->uri, $n);
			} while ($n);
			$this->uri = explode("/", $this->uri);
		} else {
			$this->uri = [""];
		}
	}

	private function match()
	{
		$found = false;
		$uriCount = count($this->uri);
		foreach ($this->routes as $key => $val) {
			$key = explode("/", $key);
			array_shift($key);
			if (($c=count($key)) === $uriCount) {
				$c--;
				foreach ($key as $ku => $kv) {
					if ($kv === $this->uri[$ku]) {
						if ($ku === $c) {
							$found = true;
						}
					} elseif (
						$kv[0] === "{" &&
						$kv[strlen($kv)-1] === "}"
					) {
						dd($kv);
					} else {
						$found = false;
						continue;
					}
				}
				if ($found) {
					if (! isset($val[$_SERVER["REQUEST_METHOD"]])) {
						$this->action = new MethodNotAllowedException("Method not allowed");
					}
					$this->action = $val[$_SERVER["REQUEST_METHOD"]];
					break;
				}
			}
		}
		return $found;
	}

	public function getAction()
	{
		return $this->action;
	}
}
