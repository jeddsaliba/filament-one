<?php

namespace Database\Seeders;

use App\Models\ApiIntegration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApiIntegrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApiIntegration::factory()->count(15)->create();
    }
}
