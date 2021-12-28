<?php

namespace TLT\Util\Enum;

class Constants {
	const URI_PREFIX = '~20006203';
	const API_PREFIX = 'api';

	final public static function AVATAR_URL_PREFIX() {
		return self::SERVER_URL_PREFIX() . 'avatar';
	}

	final public static function SERVER_URL_PREFIX() {
		return "https://$_SERVER[HTTP_HOST]/~20006203/api/";
	}

	final public static function SHOW_IMAGE_URL_PREFIX() {
		return self::SERVER_URL_PREFIX() . 'show/image';
	}
}
