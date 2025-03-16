<?php

namespace Database\Seeders;

use App\Models\Inbox;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InboxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Inbox::factory()->count(25)->create();
    }
}
