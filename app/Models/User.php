<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Support\EmailFormatter;
use App\Support\NameFormatter;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string|null $first_name
 * @property string|null $middle_name
 * @property string|null $last_name
 * @property string|null $suffix
 * @property string|null $fullname
 *
 * @phpstan-property string|null $fullname
 *
 * @property-read string $full_name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['first_name', 'middle_name', 'last_name', 'suffix', 'fullname', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @return Attribute<string|null, string|null>
     */
    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => $value,
            set: fn (mixed $value) => NameFormatter::name($value),
        );
    }

    /**
     * @return Attribute<string|null, string|null>
     */
    protected function middleName(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => $value,
            set: fn (mixed $value) => NameFormatter::middle($value),
        );
    }

    /**
     * @return Attribute<string|null, string|null>
     */
    protected function lastName(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => $value,
            set: fn (mixed $value) => NameFormatter::name($value),
        );
    }

    /**
     * @return Attribute<string|null, string|null>
     */
    protected function suffix(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => $value,
            set: fn (mixed $value) => NameFormatter::suffix($value),
        );
    }

    /**
     * @return Attribute<string|null, string|null>
     */
    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => $value,
            set: fn (mixed $value) => EmailFormatter::sanitize($value),
        );
    }

    public function initials(): string
    {
        $parts = array_filter([
            NameFormatter::name($this->first_name),
            NameFormatter::name($this->last_name),
        ]);

        return Str::of(implode(' ', $parts))
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * @return Attribute<string, never>
     */
    protected function fullName(): Attribute
    {
        return Attribute::get(fn () => NameFormatter::full(
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->suffix,
        ));
    }
}
