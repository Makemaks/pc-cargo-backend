<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\JobRevenueLine;
use Illuminate\Database\Seeder;

class JobRevenueLineSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Job::all() as $job) {
            JobRevenueLine::create([
                'job_id' => $job->id,
                'description' => 'Freight charge',
                'amount' => 12000,
                'currency'    => 'USD',
            ]);
        }
    }
}
