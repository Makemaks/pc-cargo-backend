<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\JobNote;
use App\Models\User;
use Illuminate\Database\Seeder;

class JobNoteSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Job::all() as $job) {
            JobNote::create([
                'job_id' => $job->id,
                'note' => 'Initial job setup completed.',
            ]);
        }
    }
}
