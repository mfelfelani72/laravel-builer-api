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
}
