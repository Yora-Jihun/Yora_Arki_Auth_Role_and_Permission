<?php

namespace App\Support;

use Illuminate\Support\Str;

class NameFormatter
{
    public static function name(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        return Str::of($value)
            ->squish()
            ->title()
            ->toString();
    }

    public static function middle(mixed $value): ?string
    {
        return self::name($value);
    }

    public static function middleInitial(mixed $value): ?string
    {
        $value = self::name($value);

        if ($value === null) {
            return null;
        }

        $initial = Str::of($value)
            ->explode(' ')
            ->filter()
            ->first();

        return $initial === null ? null : Str::substr($initial, 0, 1).'.';
    }

    public static function suffix(mixed $value): ?string
    {
        $value = self::name($value);

        if ($value === null) {
            return null;
        }

        return match (Str::lower(Str::replace('.', '', $value))) {
            'jr' => 'Jr.',
            'sr' => 'Sr.',
            'ii', '2', '2nd' => 'II',
            'iii', '3', '3rd' => 'III',
            'iv', '4', '4th' => 'IV',
            'v', '5', '5th' => 'V',
            default => $value,
        };
    }

    public static function full(mixed $first, mixed $middle, mixed $last, mixed $suffix): string
    {
        return implode(' ', array_filter([
            self::name($first),
            self::middleInitial($middle),
            self::name($last),
            self::suffix($suffix),
        ]));
    }
}
