<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use App\Support\EmailFormatter;
use App\Support\NameFormatter;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
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
 * @property UserRole $role
 * @property string|null $employee_id
 * @property string|null $employer_id
 *
 * @phpstan-property string|null $fullname
 *
 * @property-read string $full_name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property string|null $country_code
 * @property string|null $contact_to
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['first_name', 'middle_name', 'last_name', 'suffix', 'fullname', 'role', 'employee_id', 'employer_id', 'email', 'password', 'country_code', 'contact_to', 'avatar'])]
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
            'role' => UserRole::class,
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $user): void {
            if ($user->isEmployee() && $user->employee_id === null) {
                $user->employee_id = self::generateIdentityId('EMPL');
            }

            if ($user->isEmployer() && $user->employer_id === null) {
                $user->employer_id = self::generateIdentityId('EMPR');
            }
        });
    }

    private static function generateIdentityId(string $prefix): string
    {
        do {
            $identityId = $prefix.'-'.Str::upper(Str::random(8));
        } while (self::query()->where('employee_id', $identityId)->orWhere('employer_id', $identityId)->exists());

        return $identityId;
    }

    public function hasRole(UserRole|string $role): bool
    {
        $expectedRole = $role instanceof UserRole ? $role : UserRole::from($role);

        return $this->role === $expectedRole;
    }

    public function isEmployee(): bool
    {
        return $this->hasRole(UserRole::Employee);
    }

    public function isEmployer(): bool
    {
        return $this->hasRole(UserRole::Employer);
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            UserRole::Employer => 'Employer',
            UserRole::Employee => 'Employee',
        };
    }

    /**
     * @return BelongsToMany<Company, $this, Pivot>
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class)
            ->withPivot('department_id')
            ->withTimestamps();
    }

    /**
     * @return HasMany<Attendance, $this>
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
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
