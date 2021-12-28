<?php

namespace TLT\Util\Data;

use Exception;
use TLT\Util\HttpUtil;
use TLT\Util\Log\Logger;

class DataUtil {
	public static function readOrDefault($fileName, $default, $headers) {
		$path = __DIR__ . '/../../../data/';

		if (file_exists($path . $fileName)) {
			self::read($fileName, $headers);
		} elseif (file_exists($path . $default)) {
			self::read($default, $headers);
		} else {
			Logger::getInstance()->fatal(
				"File did not exist at fileName ($fileName) or default ($default)"
			);
		}
	}

	public static function read($fileName, $headers) {
		HttpUtil::applyHeaders($headers);
		try {
			readfile(__DIR__ . "/../../../data/$fileName");
		} catch (Exception $ex) {
			Logger::getInstance()->error($ex);
			Logger::getInstance()->fatal("Failed to read file $fileName");
		}
	}
}
