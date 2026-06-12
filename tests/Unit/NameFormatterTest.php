<?php

namespace Tests\Unit;

use App\Support\EmailFormatter;
use App\Support\NameFormatter;
use PHPUnit\Framework\TestCase;

class NameFormatterTest extends TestCase
{
    public function test_formats_full_name_with_title_case_middle_initial_and_suffix(): void
    {
        $this->assertSame(
            'John A. Doe Jr.',
            NameFormatter::full(' JOhn ', ' albert ', ' doe ', ' jr '),
        );
    }

    public function test_formats_full_name_with_roman_suffix(): void
    {
        $this->assertSame(
            'John A. Doe III',
            NameFormatter::full('John', 'A.', 'Doe', 'III'),
        );
    }

    public function test_formats_full_name_without_optional_parts(): void
    {
        $this->assertSame(
            'John A. Doe',
            NameFormatter::full('John', 'A.', 'Doe', ''),
        );
    }

    public function test_formats_middle_name_without_converting_it_to_initial(): void
    {
        $this->assertSame(
            'Apple',
            NameFormatter::middle(' apple '),
        );
    }

    public function test_formats_middle_initial(): void
    {
        $this->assertSame(
            'A.',
            NameFormatter::middleInitial(' apple '),
        );
    }

    public function test_sanitizes_email(): void
    {
        $this->assertSame(
            'ayenamitsuki24@gmail.com',
            EmailFormatter::sanitize(' AyEnAMitSuki24@gmail.com '),
        );
    }
}
