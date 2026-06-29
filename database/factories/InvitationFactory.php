<?php

namespace Database\Factories;

use App\Enums\InvitationStatus;
use App\Models\Company;
use App\Models\Invitation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Invitation>
 */
class InvitationFactory extends Factory
{
    protected $model = Invitation::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'email' => fake()->safeEmail(),
            'token' => Str::random(32),
            'status' => InvitationStatus::Pending,
        ];
    }
}
