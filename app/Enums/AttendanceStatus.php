<?php

namespace App\Enums;

enum AttendanceStatus: string
{
    case Pending = 'pending';
    case CheckedIn = 'checked_in';
    case CheckedOut = 'checked_out';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::CheckedIn => 'Checked In',
            self::CheckedOut => 'Checked Out',
        };
    }
}
