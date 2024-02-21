<?php

namespace Api;

class Response
{
    public static function json(bool $status, ?string $message = null, $data = null, int $statusCode = 200)
    {
        http_response_code($statusCode);

        $payload = [
            "status" => $status,
        ];

        if ($message) {
            $payload["message"] = $message;
        }

        if ($data) {
            $payload["data"] = $data;
        }

        die(json_encode($payload));
    }
}
