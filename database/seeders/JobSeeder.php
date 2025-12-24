<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\Client;
use App\Enums\JobStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Client::all() as $index => $client) {
            $isCompleted = $index === 0;

            Job::create([
                'job_reference'  => 'JOB-2024-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'client_id'      => $client->id,
                'status'         => $isCompleted
                    ? JobStatus::Completed
                    : JobStatus::InTransit,
                'payment_status' => $isCompleted
                    ? PaymentStatus::Paid
                    : PaymentStatus::Unpaid,
                'completed_at'   => $isCompleted
                    ? Carbon::now()->subDays(2)
                    : null,
            ]);
        }
    }
}
