<?php

require_once __DIR__ . '/../util/constant/ContentType.php';
require_once __DIR__ . '/../util/constant/StatusCode.php';
require_once __DIR__ . '/../util/constant/CORS.php';

class Response {
    public function send($data, ...$headers) {
        header_remove('Set-Cookie');

        foreach ($headers as $header) {
            header($header);
        }

        echo $data;
        exit;
    }

    public function sendJSON($data, ...$headers) {
        if (is_array($data)) {
            $this -> send(json_encode($data), CORS::ALL, ContentType::JSON, ...$headers);
        }
        else if (is_string($data)) {
            $this -> send(json_encode(json_decode($data)), CORS::ALL, ContentType::JSON, ...$headers); // encode/decode for validation
        }
        else {
            throw new UnexpectedValueException("Expected JSON-like data");
        }
    }

    public function sendError($message, ...$headers) {
        $this -> send(json_encode([
            "error" => true,
            "message" => $message
        ]), ContentType::JSON, CORS::ALL, ...$headers);
    }
}