<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\JobTransport;
use Illuminate\Database\Seeder;

class JobTransportSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Job::all() as $job) {
            JobTransport::create([
                'job_id' => $job->id,
                'transport_mode' => 'sea',
                'origin' => 'Manila',
                'destination' => 'Singapore',
            ]);
        }
    }
}
