<?php

namespace EsTeh\Foundation;

use EsTeh\Http\Response\Body;
use EsTeh\Routing\RouteNaming;
use EsTeh\Http\Response\Header;
use EsTeh\Routing\RouteMatching;
use EsTeh\Routing\RouteCollection;
use EsTeh\Foundation\Http\Middleware;
use EsTeh\Foundation\Http\NextMiddleware;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\Foundation
 * @license MIT
 */
class HttpAction
{
	public static function action($providers)
	{
		$responses = [];

		foreach ($providers as $provider) {
			$provider = new $provider();
			$provider->boot();
		}

		$st = new RouteMatching(
			RouteCollection::getAll()
		);

		RouteNaming::buildRouteNames();

		if (! $st->uri()) {
			$httpCode = 404;
			$action = function () {
				echo "Not Found";
			};
		}

		if ($st->method()) {
			$httpCode = 200;
			$action = $st->getAction();
		} else {
			$httpCode = 405;
			$action = function () {
				echo "Method not allowed";
			};
		}

		if ($httpCode === 200) {
			$st = new Middleware();
			$st->initMiddleware();
			$lastMiddlewareReturn = $st->latestMiddlewareReturn();

			if (! ($lastMiddlewareReturn instanceof NextMiddleware)) {
				$action = $lastMiddlewareReturn;
			}
		}

		$st = new Header(["http_response_code" => $httpCode]);
		$st->buildHeader();

		$responses[] = $st;

		$st = new Body($action);
		$st->buildBody($responses[0]);

		$responses[] =  $st;

		return $responses;
	}
}
