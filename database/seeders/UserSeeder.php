<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use App\Models\UserProfile;
use BezhanSalleh\FilamentShield\Support\Utils;
use Carbon\Carbon;
use Database\Factories\ClientFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(25)->has(UserProfile::factory())->create()->each(function ($user) {
            Utils::createPanelUserRole();
            $user->assignRole(Utils::getPanelUserRoleName());
        });
    }
}
