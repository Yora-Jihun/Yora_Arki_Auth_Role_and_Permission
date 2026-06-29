<?php

namespace App\Enums;

enum UserRole: string
{
    case Employee = 'employee';
    case Employer = 'employer';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
