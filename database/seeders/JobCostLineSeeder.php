<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\JobCostLine;
use Illuminate\Database\Seeder;

class JobCostLineSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Job::all() as $job) {
            JobCostLine::create([
                'job_id'      => $job->id,
                'description' => 'Port handling fee',
                'amount'      => 3500,
                'currency'    => 'USD',
            ]);

            JobCostLine::create([
                'job_id'      => $job->id,
                'description' => 'Customs clearance',
                'amount'      => 2200,
                'currency'    => 'USD',
            ]);
        }
    }
}
