<?php

namespace EsTeh\Session;

use EsTeh\Hub\Singleton;
use EsTeh\Support\Config;

class Session
{
	use Singleton;

	/**
	 * @var string
	 */
	private $file;

	/**
	 * @var bool
	 */
	private $destroyed = false;

	/**
	 * @var int
	 */
	private $expiredAt;

	/**
	 * @var string
	 */
	private $sessionId;

	/**
	 * @var string
	 */
	private $cookieName;

	/**
	 * @var array
	 */
	private $sessionContainer = [];

	/**
	 * @var int
	 */
	private $sessionLifeTime;

	/**
	 *
	 * Constructor.
	 */
	protected function __construct()
	{
		$config = Config::get('app')['session'];
		$this->cookieName = $config['cookie_name'];
		$this->sessionLifeTime = $config['expired'];
		if (isset($_COOKIE[$this->cookieName])) {
			$this->sessionId = $_COOKIE[$this->cookieName];
			$this->file = $config['session_path'].'/'.$this->sessionId;
			if (file_exists($this->file)) {
				$container = unserialize(ice_decrypt(file_get_contents($this->file), Config::get('app')['key'], false));
				if (isset($container['expired_at'], $container['container'])) {
					$this->expiredAt = $container['expired_at'];
					$this->sessionContainer = $container['container'];
				} else {
					$this->buildCookie($config['session_path']);
				}
			} else {
				$this->buildCookie($config['session_path']);
			}
		} else {
			$this->buildCookie($config['session_path']);
		}
	}

	/**
	 * @param string $configPath
	 */
	private function buildCookie($configPath)
	{
		$this->sessionId = rstr(32);
		$this->file = $configPath.'/'.$this->sessionId;
		setcookie($this->cookieName, $this->sessionId, $this->expiredAt = time() + $this->sessionLifeTime, '/');
	}

	/**
	 * @param string $key
	 * @return mixed
	 */
	public function __get($key)
	{
		return isset($this->{$key}) ? $this->{$key} : null;
	}

	/**
	 * @param string $key|int
	 * @return mixed
	 */
	public function get($key)
	{
		return array_key_exists($key, $this->sessionContainer) ? $this->sessionContainer[$key] : null;
	}

	/**
	 * @param string|int $key
	 * @param mixed 	 $value
	 */
	public function set($key, $value)
	{
		$this->sessionContainer[$key] = $value;
	}

	public function destroy()
	{
		unset($this->sessionContainer);
		if (file_exists($this->file)) {
			unlink($this->file);
		}
		$this->destroyed = true;
	}

	public function getAll()
	{
		return $this->sessionContainer;
	}

	public function __destruct()
	{
		if (! $this->destroyed) {
			file_put_contents($this->file, $this->serializeContainer());
		}
	}

	/**
	 * @return string
	 */
	private function serializeContainer()
	{
		return ice_encrypt(serialize(
			[
				'expired_at' => $this->expiredAt,
				'container' => $this->sessionContainer
			]
		), Config::get('app')['key'], false);
	}
}
