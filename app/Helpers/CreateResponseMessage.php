<?php

namespace App\Helpers;

class CreateResponseMessage
{
    /**
     * create response message
     *
     * @param string $message
     * @param object $error
     * @return array
     */
    public static function Error(string $message, object $error): array
    {
        return [
            "return" => false,
            "message" => $message,
            "errors" => $error,
        ];
    }

    /**
     * create success message
     *
     * @param string $text
     * @param object $record
     * @return array
     */
    public static function Success(string $message, object $record): array
    {
        return [
            "return" => true,
            "message" => $message,
            "record" => $record,
        ];
    }
}
