<?php

namespace EsTeh\Support;

use EsTeh\Http\Json\Json;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Support
 * @license MIT
 */
class Response
{
	public function json($data, $httpCode = 200, $jsonOpt = null)
	{
		return new Json($data, $httpCode, $jsonOpt);
	}
}
