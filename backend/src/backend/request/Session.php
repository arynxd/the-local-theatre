<?php

namespace TLT\Request;

use Exception;
use TLT\Middleware\BaseMiddleware;
use TLT\Request\Module\Impl\AuthModule;
use TLT\Request\Module\Impl\CacheModule;
use TLT\Request\Module\Impl\ConfigModule;
use TLT\Request\Module\Impl\DatabaseModule;
use TLT\Request\Module\Impl\DataModule;
use TLT\Request\Module\Impl\HttpModule;
use TLT\Request\Module\Impl\RoutingModule;
use TLT\Util\Data\Map;
use TLT\Util\Enum\ParamSource;
use TLT\Util\Log\Logger;

/**
 * Central object representing a single connection, a session.
 *
 * Holds various modules to interact with all aspects of the application
 *
 * This class should only be constructed once.
 */
class Session {
	/**
	 * The response object for this session
	 *
	 * @var Response $res
	 */
	public $res;

	/**
	 * The HTTP module for this session
	 *
	 * @var HttpModule $http
	 */
	public $http;

	/**
	 * The routing module for this session
	 *
	 * @var RoutingModule $routing
	 */
	public $routing;

	/**
	 * The data module for this session
	 *
	 * @var DataModule $data
	 */
	public $data;

	/**
	 * The cache module for this session
	 *
	 * @var CacheModule $cache
	 */
	public $cache;

	/**
	 * The config module for this session
	 *
	 * @var ConfigModule $cfg
	 */
	public $cfg;

	/**
	 * The auth module for this session
	 *
	 * @var AuthModule $auth
	 */
	public $auth;

	/**
	 * The database module for this session
	 *
	 *
	 * @var DatabaseModule $db
	 */
	public $db;

	private $parsedJSON;

	public function __construct() {
		Logger::getInstance()->info('Loading modules...');
		$this->res = new Response($this);

		// init modules
		$this->cfg = new ConfigModule($this);
		$this->http = new HttpModule($this);
		$this->routing = new RoutingModule($this);
		$this->data = new DataModule($this);
		$this->cache = new CacheModule($this);
		$this->auth = new AuthModule($this);
		$this->db = new DatabaseModule($this);
		Logger::getInstance()->info(
			'Modules constructed without error, enabling'
		);

		$all = [
			$this->cfg,
			$this->http,
			$this->routing,
			$this->data,
			$this->cache,
			$this->db,
			$this->auth,
		];

		foreach ($all as $mod) {
			try {
				Logger::getInstance()->debug(
					"\tEnabling module " . get_class($mod)
				);
				$mod->onEnable();
			} catch (Exception $ex) {
				Logger::getInstance()->error(
					'Module ' .
						get_class($mod) .
						' encountered an error whilst enabling'
				);
				$this->res->internal($ex);
			}
		}
		Logger::getInstance()->info('Modules loaded');
	}

	/**
	 * Parses the body of the request and returns it as a Map
	 *
	 * If the body cannot be parsed, a warning will be logged and an empty map returned
	 *
	 * @return Map
	 */
	public function jsonParams() {
		return $this->parseParams(ParamSource::JSON);
	}

	/**
	 * Parses the query parameters provided in the URL
	 *
	 * @return Map
	 */
	public function queryParams() {
		return $this->parseParams(ParamSource::QUERY);
	}

	private function parseParams($source) {
		Logger::getInstance()->info("Parsing params from source $source");
		if ($source == ParamSource::QUERY) {
			Logger::getInstance()->debug(
				'Query params selected, returning $_GET'
			);
			return Map::from($_GET);
		} elseif ($source == ParamSource::JSON) {
			Logger::getInstance()->debug('Attempting JSON parse..');

			if (isset($this->parsedJSON)) {
				Logger::getInstance()->debug('Using cached JSON..');
				Logger::getInstance()->debug("\t{$this->parsedJSON}");
				return $this->parsedJSON;
			}

			$raw = file_get_contents('php://input');

			if (!$raw) {
				$raw = '{}'; //  if no body is passed, default to empty obj
			}

			$result = json_decode($raw, true);

			if (!isset($result)) {
				Logger::getInstance()->warn(
					'JSON parse failed for input, falling back to empty map'
				);
				Logger::getInstance()->warn($raw);
				return Map::none();
			}

			Logger::getInstance()->debug('JSON parse succeeded!');

			$result = Map::from($result)->toMapRecursive();

			$this->parsedJSON = $result;
			return $result;
		}

		Logger::getInstance()->fatal("Unexpected ParamSource $source");
	}

	/**
	 * Applies a middleware to the session
	 *
	 * If the middleware fails, the connection is terminated with an error
	 *
	 * @param BaseMiddleware $middleware The middlewares to apply
	 */
	public function applyMiddleware($middleware) {
		Logger::getInstance()->fatal("Calling Session#applyMiddlware is an error, please use RoutingModule#middleware instead");
	}
}
