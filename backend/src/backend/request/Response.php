<?php

namespace TLT\Request;

use Exception;
use TLT\Util\Assert\Assertions;
use TLT\Util\Data\DataUtil;
use TLT\Util\Data\Map;
use TLT\Util\Data\MapUtil;
use TLT\Util\Enum\ContentType;
use TLT\Util\Enum\CORS;
use TLT\Util\Enum\ErrorStrings;
use TLT\Util\Enum\StatusCode;
use TLT\Util\HttpUtil;
use TLT\Util\Log\Logger;
use UnexpectedValueException;

class Response {
	/**
	 * @var Session|null $sess
	 */
	private $sess;

	private $headers;

	/**
	 * Constructs a new Response based on a Session
	 *
	 * If $sess is null, some features will be unavailable
	 *
	 * @param Session|null $sess
	 */
	public function __construct($sess = null) {
		$this->sess = $sess;
		$this->headers = Map::none();
	}

	private function requireSession() {
		Assertions::assertSet($this->sess);
	}

	/**
	 * @return never
	 */
	private function send($data) {
		Assertions::assertSet($data);
		if ($this->headers->length() == 0) {
			Logger::getInstance()->warn(
				'No headers set, this is probably a bug'
			);
		}

		HttpUtil::applyHeaders($this->headers->raw());

		Logger::getInstance()->info('Sending response..');
		Logger::getInstance()->debug("\t$data");

		echo $data;
		exit(0);
	}

	/**
	 * Sends a JSON response to the client
	 *
	 * If a string is passed, it must be a valid JSON string
	 *
	 * @param string|Map|array
	 * @return never
	 */
	public function json($msg) {
		$this->content('json');

		if (MapUtil::is_map($msg) || is_array($msg)) {
			$this->send(json_encode($msg));
		} elseif (is_string($msg)) {
			$this->send(json_encode(json_decode($msg))); // encode/decode for validation
		} else {
			throw new UnexpectedValueException('Expected JSON-like data');
		}
	}

	/**
	 * Sets a status code
	 *
	 * @param int $code
	 * @return Response The current instance
	 */
	public function status($code) {
		$header = StatusCode::MAP()[$code];

		if (!isset($header)) {
			throw new UnexpectedValueException("Unknown StatusCode $code");
		}

		if (isset($this->headers['status'])) {
			throw new UnexpectedValueException('Status already set');
		}

		$this->headers['status'] = $header;
		return $this;
	}

	/**
	 * Sets a header
	 *
	 * @param string $header
	 * @return Response The current instance
	 */
	public function header($header) {
		$this->headers->push($header);
		return $this;
	}

	/**
	 * Reads from the data directory
	 *
	 * @param string $path
	 * @param string|null $default
	 * @return never
	 */
	public function data($path, $default = null) {
		if (isset($default)) {
			DataUtil::readOrDefault($path, $default, $this->headers->raw());
		} else {
			DataUtil::read($path, $this->headers->raw());
		}
		exit(0);
	}

	/**
	 * Sets the cors policy
	 *
	 * @param string $policy
	 * @return Response The current instance
	 */
	public function cors($policy) {
		$header = CORS::MAP()[$policy];

		if (!isset($header)) {
			throw new UnexpectedValueException("Unknown CORS policy $policy");
		}

		if (isset($this->headers['cors'])) {
			throw new UnexpectedValueException('CORS policy already set');
		}

		$this->headers['cors'] = $header;
		return $this;
	}

	/**
	 * Sets the content type
	 *
	 * @param string $type
	 * @return Response The current instance
	 */
	public function content($type) {
		$header = ContentType::MAP()[$type];

		if (!isset($header)) {
			throw new UnexpectedValueException("Unknown ContentType $type");
		}

		if (isset($this->headers['content'])) {
			throw new UnexpectedValueException('Content type already set');
		}

		$this->headers['content'] = $header;
		return $this;
	}

	/**
	 * Sends an internal error, $ex will NOT be sent to the client
	 *
	 * @param Exception|string $ex
	 * @return never
	 */
	public function internal($ex = 'No message set') {
		Logger::getInstance()->error('An internal error has occurred:');
		Logger::getInstance()->error("\t" . $ex);

		$this->status(500);
		$this->error(ErrorStrings::INTERNAL_ERROR);
	}

	/**
	 * Sends an error message to the client
	 *
	 * @param string $msg
	 * @return never
	 */
	public function error($msg) {
		Logger::getInstance()->error('Route returned error => ' . $msg);

		$this->json(
			Map::from([
				'error' => true,
				'message' => $msg,
			])
		);
	}
}
