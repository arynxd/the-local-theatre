<?php

namespace TLT\Util;

class HttpUtil {
  /**
   * Sends the $header
   * 
   * @param string $header The header to send
   */
  public static function applyHeader($header) {
    header($header);
  }

  /**
   * Sends all the $headers
   * 
   * @param string[] The headers to send
   */
  public static function applyHeaders($headers) {
      foreach ($headers as $h) {
        self::applyHeader($h);
      }
  }
}