<?php

namespace EsTeh\Contracts\Http;

use EsTeh\Contracts\Response as BaseResponse;

interface Response extends BaseResponse
{
	public function sendResponse();
}