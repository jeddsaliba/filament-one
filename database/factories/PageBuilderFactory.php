<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PageBuilder>
 */
class PageBuilderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->word();
        $description = fake()->unique()->sentences(3, true);
        Log::info('description', ['data' => $description]);
        return [
            'title' => Str::title($title),
            'slug' => Str::slug($title),
            'description' => $description,
            'is_active' => true,
            'meta' => [
                'description' => $description,
                'keywords' => $title,
                'author' => config('app.name')
            ]
        ];
    }
}
