<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = Carbon::now();
        $birthdate = fake()->unique()->dateTimeBetween($date->copy()->subYears('75')->startOfYear(), $date->copy()->subYears('18')->endOfYear());
        return [
            'phone' => fake()->unique()->e164PhoneNumber(),
            'birthdate' => $birthdate,
        ];
    }
}
