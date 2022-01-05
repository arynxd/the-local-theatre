<?php

namespace TLT\Request\Module\Impl;

use Exception;
use TLT\Middleware\Impl\AuthenticationMiddleware;
use TLT\Middleware\Impl\DatabaseMiddleware;
use TLT\Request\Module\BaseModule;
use TLT\Routing\BaseRoute;
use TLT\Routing\Router;
use TLT\Util\Data\Map;
use TLT\Util\Enum\StatusCode;
use TLT\Util\Log\Logger;

class RoutingModule extends BaseModule {
	/**
	 * @var Router $router
	 */
	public $router;

	/**
	 * @var BaseRoute $route
	 */
	public $route;

	private $middleWares;

	public function onEnable() {
		$this->router = new Router();
		$this->route = $this->parseRoute();
		$this->middleWares = Map::from([
			'auth' => new AuthenticationMiddleware(),
			'db' => new DatabaseMiddleware(),
		]);
	}

	/**
	 * Applies a middlware to the current session
	 * @param string $ware
	 * @return RoutingModule The current instance
	 */
	public function middlware($ware) {
		$middleware = $this->middleWares[$ware];

		if (!isset($middleware)) {
			Logger::getInstance()->warn("Unknown middleware $ware");
			return $this;
		}
		$wareResult = null;
		Logger::getInstance()->debug("Applying middleware $ware");

		try {
			$wareResult = $middleware->apply($this->sess);
		} catch (Exception $ex) {
			Logger::getInstance()->error(
				"An error occurred whilst applying middleware $ware"
			);
			$this->res->internal($ex);
		}

		if ($wareResult->isError()) {
			$this->res
				->status($wareResult->httpCode)
				->cors('all')
				->error($wareResult->error);
		}
		Logger::getInstance()->info("Middleware $ware applied without error");
		return $this;
	}

	private function parseRoute() {
		$uri = $this->sess->http->uri;
		$rawUri = $this->sess->http->rawUri;

		$result = $this->router->getRouteForPath($uri);

		if (!isset($result)) {
			$this->sess->res
				->status(404)
				->cors('all')
				->error("Route $rawUri not found");
		}

		return $result;
	}
}
