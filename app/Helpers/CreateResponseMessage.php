<?php

namespace App\Helpers;

class CreateResponseMessage
{
    /**
     * create response message
     *
     * @param string $text
     * @param object $error
     * @return array
     */
    public static function Error(string $text, object $error): array
    {
        return [
            "return" => false,
            "data" => ["message" => $text, "errors" => $error],
        ];
    }

     /**
     * create success message
     *
     * @param string $text
     * @param object $record
     * @return array
     */
    public static function Success(string $text, object $record): array
    {
        return [
            "return" => true,
            "data" => ["message" => $text, "record" => $record],
        ];
    }
}
