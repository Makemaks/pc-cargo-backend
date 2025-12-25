<?php

namespace App\Repositories\Contracts;

use App\Models\JobPayment;

interface PaymentRepositoryInterface extends BaseRepositoryInterface
{
    public function findLatestByJob(int $jobId): ?JobPayment;
}
