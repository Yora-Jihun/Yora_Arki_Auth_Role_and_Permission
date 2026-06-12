<?php

namespace App\Support;

use Illuminate\Support\Str;

class EmailFormatter
{
    public static function sanitize(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = preg_replace('/\s+/u', '', trim($value)) ?? '';

        return $value === '' ? null : Str::lower($value);
    }
}
