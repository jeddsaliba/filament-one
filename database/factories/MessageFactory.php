<?php

namespace Database\Factories;

use App\Models\Inbox;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $inbox = Inbox::inRandomOrder()->first();
        $users = $inbox ? $inbox->user_ids : [];
        $sender = $this->faker->randomElement($users);

        $readBy = $this->faker->randomElements($users, $this->faker->numberBetween(0, count($users)));
        $readAt = array_map(fn() => Carbon::parse($this->faker->dateTimeThisYear())->toDateTimeString(), $readBy);

        return [
            'inbox_id' => $inbox->id ?? Inbox::factory(),
            'message' => $this->faker->sentence(),
            'user_id' => $sender,
            'read_by' => $readBy,
            'read_at' => $readAt,
            'notified' => $readBy,
        ];
    }
}
