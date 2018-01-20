<?php

namespace EsTeh\Support;

trait ArrayUtils
{
	/**
	 * @var array
	 */
	private $___storage = [];

	/**
	 * @param mixed $key
	 * @param mixed $value
	 * @return void
	 */
	public function offsetSet($key, $value)
	{
		if (is_null($key)) {
			$this->___storage[] = $value;
			return;
		}
		$this->___storage[$key] = $value;
	}

	/**
	 * @param mixed $key
	 * @return mixed
	 */
	public function &offsetGet($key)
	{
		return $this->___storage[$key];
	}

	/**
	 * @param mixed $key
	 * @return bool
	 */
	public function offsetExists($key)
	{
		return array_key_exists($key, $this->___storage);	
	}

	/**
	 * @param mixed $key
	 * @return void
	 */
	public function offsetUnset($key)
	{
		unset($this->___storage[$key]);
	}

	/**
	 * @param mixed $key
	 * @return mixed
	 */
	public function &__get($key)
	{
		$key = $this->offsetGet($key);
		return $key;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return $this->___storage;
	}
}
