<?php

namespace EsTeh\Contracts\Http;

use EsTeh\Contracts\Response as BaseResponse;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Contracts\Http
 * @license MIT
 */
interface Response extends BaseResponse
{
	public function sendResponse();
}
