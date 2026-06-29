<?php

namespace App\Models;

use App\Enums\InvitationStatus;
use Database\Factories\InvitationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

#[Fillable(['company_id', 'department_id', 'invited_by', 'accepted_by', 'employee_id', 'email', 'token', 'status', 'accepted_at', 'expires_at'])]
class Invitation extends Model
{
    /** @use HasFactory<InvitationFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'accepted_at' => 'datetime',
            'expires_at' => 'datetime',
            'status' => InvitationStatus::class,
        ];
    }

    /**
     * @return BelongsTo<Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return BelongsTo<Department, $this>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function acceptedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'accepted_by');
    }

    /**
     * @return Attribute<mixed, mixed>
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            set: fn (InvitationStatus|string $value) => $value instanceof InvitationStatus ? $value->value : $value,
        );
    }

    public function isPending(): bool
    {
        return $this->status === InvitationStatus::Pending;
    }

    public function isExpired(): bool
    {
        $expiresAt = $this->getAttribute('expires_at');

        return $expiresAt instanceof Carbon && $expiresAt->isPast();
    }

    public function canBeAcceptedBy(User $user): bool
    {
        return $this->isPending()
            && ! $this->isExpired()
            && ($this->employee_id === $user->id || $this->email === $user->email);
    }

    public function markAccepted(User $user): void
    {
        $this->update([
            'accepted_by' => $user->id,
            'status' => InvitationStatus::Accepted,
            'accepted_at' => Carbon::now(),
        ]);
    }
}