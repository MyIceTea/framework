<?php

namespace EsTeh\Foundation;

use EsTeh\Hub\Singleton;
use EsTeh\Contracts\Abilities\Captureable;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Foundation
 * @license MIT
 */
abstract class HttpCapture implements Captureable
{
	use Singleton;

	protected $captureStatus = false;

	/**
	 * @return bool
	 */
	public static function capture()
	{
		return self::getInstance()->captureStatus;
	}

	abstract protected function __construct();
}
