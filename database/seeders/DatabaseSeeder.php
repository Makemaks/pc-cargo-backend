<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ClientSeeder::class,
            JobSeeder::class,
            JobTransportSeeder::class,
            JobCostLineSeeder::class,
            JobRevenueLineSeeder::class,
            JobAdjustmentLineSeeder::class,
            JobPaymentSeeder::class,
            JobNoteSeeder::class,
        ]);
    }
}
