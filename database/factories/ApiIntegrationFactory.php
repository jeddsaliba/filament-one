<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApiIntegration>
 */
class ApiIntegrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->word();
        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            'is_active' => true,
            'keys' => [
                'api_key' => fake()->unique()->regexify('[A-Z0-9]{10}'),
                'api_secret' => fake()->unique()->regexify('[A-Z0-9]{10}')
            ]
        ];
    }
}
