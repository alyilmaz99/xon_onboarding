<?php

class ErrorHandler
{
    public static function handleException(Throwable $exception): void
    {
        http_response_code(500);

        echo json_encode([
            "code" => $exception->getCode(),
            "message" => $exception->getMessage(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine(),
        ]);
    }
    public static function handleError(
        int $errno,
        string $errStr,
        string $errFile,
        int $errLine
    ): bool {
        throw new ErrorException($errStr, 0, $errno, $errFile, $errLine);
    }
    public static function dataErrorHandler(array $data, array $requirements)
    {
        if (empty($data)) {
            return ['Invalid input data or requirements.'];
        }

        $errors = [];
        foreach ($requirements as $value) {
            if (!array_key_exists($value, $data)) {
                $errors[] = "$value is missing";
            }
        }

        return $errors;
    }
}
