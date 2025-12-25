<?php

namespace App\Repositories;

use App\Models\JobPayment;
use App\Repositories\Contracts\PaymentRepositoryInterface;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    public function __construct(JobPayment $model)
    {
        $this->model = $model;
    }

    /**
     * ==========================
     * Payment-specific queries
     * ==========================
     */
    public function findLatestByJob(int $jobId): ?JobPayment
    {
        return $this->model
            ->newQuery()
            ->where('job_id', $jobId)
            ->latest()
            ->first();
    }
}
