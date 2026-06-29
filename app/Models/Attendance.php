<?php

namespace App\Models;

use App\Enums\AttendanceStatus;
use Database\Factories\AttendanceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

#[Fillable(['user_id', 'company_id', 'department_id', 'date', 'check_in_at', 'check_out_at', 'status'])]
class Attendance extends Model
{
    /** @use HasFactory<AttendanceFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'check_in_at' => 'datetime',
            'check_out_at' => 'datetime',
            'status' => AttendanceStatus::class,
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
     * @return Attribute<mixed, mixed>
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            set: fn (AttendanceStatus|string $value) => $value instanceof AttendanceStatus ? $value->value : $value,
        );
    }

    public function checkIn(Carbon $now): void
    {
        $this->update([
            'check_in_at' => $now,
            'check_out_at' => null,
            'status' => AttendanceStatus::CheckedIn,
        ]);
    }

    public function checkOut(Carbon $now): void
    {
        $this->update([
            'check_out_at' => $now,
            'status' => AttendanceStatus::CheckedOut,
        ]);
    }
}