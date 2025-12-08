<?php

namespace App\Helpers;

class ReplacementSerialGenerator
{
    public static function generate(string $glNumber): string
    {
        $timestamp = now()->format('YmdHis');

        return sprintf(
            "SWA-REPLACEMENT-%s-%s",
            $timestamp,
            $glNumber
        );
    }
}
