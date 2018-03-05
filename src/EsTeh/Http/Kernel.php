<?php

namespace EsTeh\Http;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Http
 * @license MIT
 */
class Kernel
{
	protected $webRoutesFile;

	protected $apiRoutesFile;

	protected $middlewareAliases = [];

	protected $webMiddleware = [];

	protected $apiMiddleware = [];

	final public function init()
	{
	}

	public function getMiddlewareAliases()
	{
		return $this->middlewareAliases;
	}

	public function getWebMiddlewares()
	{
		return $this->webMiddleware;
	}

	public function getApiMiddlewares()
	{
		return $this->apiMiddleware;
	}
}
