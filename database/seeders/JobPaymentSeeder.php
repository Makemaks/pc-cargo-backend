<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\JobPayment;
use App\Enums\JobStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class JobPaymentSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Job::where('status', JobStatus::Completed)->get() as $job) {
            JobPayment::create([
                'job_id'            => $job->id,
                'payment_method'    => PaymentMethod::Stripe,
                'status'            => PaymentStatus::Paid,
                'amount'            => 11500,          // example paid amount
                'currency'          => 'USD',
                'external_reference'=> 'STRIPE-DEMO-' . $job->id,
                'received_at'       => Carbon::now()->subDay(),
            ]);
        }
    }
}
