<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inbox>
 */
class InboxFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = User::pluck('id')->toArray();
        $userCount = $this->faker->numberBetween(2, min(5, count($users))); 
        $selectedUsers = $this->faker->randomElements($users, $userCount);

        return [
            'title' => count($selectedUsers) > 2 ? $this->faker->sentence(3) : null,
            'user_ids' => $selectedUsers, // This will be cast to JSON in your model
        ];
    }
}
