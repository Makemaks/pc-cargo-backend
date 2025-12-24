<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\JobAdjustmentLine;
use App\Enums\JobStatus;
use Illuminate\Database\Seeder;

class JobAdjustmentLineSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Job::where('status', JobStatus::Completed)->get() as $job) {
            JobAdjustmentLine::create([
                'job_id'       => $job->id,
                'type'         => 'discount', // or 'surcharge'
                'description'  => 'Post-completion discount',
                'amount_delta' => -500,
                'currency'     => 'USD',
            ]);
        }
    }
}
