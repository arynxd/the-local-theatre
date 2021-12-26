<?php

namespace TLT\Util;

use TLT\Util\Assert\Assertions;

class AuthUtil {
	const TOKEN_LENGTH = 32;

	public static function generateToken() {
		$tok = openssl_random_pseudo_bytes(self::TOKEN_LENGTH);
		Assertions::assertNotFalse($tok);
		return bin2hex($tok);
	}

	public static function verifyPassword($rawPassword, $hashed) {

		// Credit: https://www.php.net/manual/en/function.crypt.php comments

		/* Regenerating the with an available hash as the options parameter should
		 * produce the same hash if the same password is passed.
		 */
		return crypt($rawPassword, $hashed) == $hashed;
	}

	public static function hashPassword($rawPassword, $cost = 11) {
		// Credit: https://www.php.net/manual/en/function.crypt.php comments

		/* To generate the salt, first generate enough random bytes. Because
		 * base64 returns one character for each 6 bits, the we should generate
		 * at least 22*6/8=16.5 bytes, so we generate 17. Then we get the first
		 * 22 base64 characters
		 */
		$salt = substr(base64_encode(openssl_random_pseudo_bytes(17)), 0, 22);
		/* As blowfish takes a salt with the alphabet ./A-Za-z0-9 we have to
		 * replace any '+' in the base64 string with '.'. We don't have to do
		 * anything about the '=', as this only occurs when the b64 string is
		 * padded, which is always after the first 22 characters.
		 */
		$salt = str_replace('+', '.', $salt);
		/* Next, create a string that will be passed to crypt, containing all
		 * of the settings, separated by dollar signs
		 */
		$param =
			'$' .
			implode('$', [
				'2y', //select the most secure version of blowfish (>=PHP 5.3.7)
				str_pad($cost, 2, '0', STR_PAD_LEFT), //add the cost in two digits
				$salt, //add the salt
			]);

		//now do the actual hashing
		return crypt($rawPassword, $param);
	}
}
