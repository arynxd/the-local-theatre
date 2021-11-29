<?php

namespace TLT\Request;

use Exception;
use TLT\Util\Data\Map;
use TLT\Util\Data\MapUtil;
use TLT\Util\Enum\ContentType;
use TLT\Util\Enum\CORS;
use TLT\Util\Enum\ErrorStrings;
use TLT\Util\Enum\StatusCode;
use UnexpectedValueException;

/**
 * Utility object for sending responses to the client
 * This can be freely constructed wherever needed however the Session
 * always holds an instance (Session::$res)
 *
 * @see Session
 */
class Response {
    /**
     * Sends JSON to the client
     *
     * This function will end the program exit code 0, stopping execution
     *
     * @param Map|string $data a Map, or string, representing the JSON to send in the response
     * @param string[] $headers the headers to include in the response
     *
     * @return never-return This function never returns
     */
    public function sendJSON($data, ...$headers) {
        if (MapUtil ::is_map($data)) {
            $this -> send(json_encode($data), CORS::ALL, ContentType::JSON, ...$headers);
        }
        else if (is_string($data)) {
            $this -> send(json_encode(json_decode($data)), CORS::ALL, ContentType::JSON, ...$headers); // encode/decode for validation
        }
        else if (is_array($data)) {
            throw new UnexpectedValueException("Got array passed to sendJSON, did you forget to call map()?");
        }
        else {
            throw new UnexpectedValueException("Expected JSON-like data");
        }
    }

    /**
     * Sends arbitrary data to the client.
     * This method performs NO validation on the input, use it with caution.
     *
     * This function will end the program exit code 0, stopping execution
     *
     * @param mixed $data the data to send in the response
     * @param string[] $headers the headers to send in the response
     *
     * @return never-return This function never returns
     */
    public function send($data, ...$headers) {
        foreach ($headers as $header) {
            header($header);
        }

        echo $data;
        exit(0);
    }

    /**
     * Sends a generic internal error response
     *
     * This function will kill the program, stopping execution
     *
     * @param Exception|string|null $msg The message / exception to print
     * @return never-return This function never returns
     */
    public function sendInternalError($msg = null) {
        if (is_a('Exception', $msg)) {
            $msg = $msg -> getMessage();
        }

        error_log(ErrorStrings::INTERNAL_ERROR . PHP_EOL . PHP_EOL . $msg);
        $this -> sendError(ErrorStrings::INTERNAL_ERROR, StatusCode::INTERNAL_ERROR);
    }

    /**
     * Sends an error message to the client
     *
     * This function will kill the program, stopping execution
     *
     * @param string $message the message to send in the response, must be JSON serializable
     * @param string[] $headers the headers to send in the response
     *
     * @return never-return This function never returns
     */
    public function sendError($message, ...$headers) {
        $this -> send(json_encode([
            "error" => true,
            "message" => $message
        ]), ContentType::JSON, CORS::ALL, ...$headers);
    }
}
