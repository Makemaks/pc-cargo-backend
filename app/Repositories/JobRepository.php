<?php

namespace App\Repositories;

use App\Models\Job;
use App\Repositories\Contracts\JobRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class JobRepository extends BaseRepository implements JobRepositoryInterface
{
    public function __construct(Job $job)
    {
        $this->model = $job;
    }

    /**
     * Fetch all jobs with financial relations loaded
     * (for JobResource + JobFinancialHelper)
     */
    public function allWithFinancials(): Collection
    {
        return $this->model
            ->newQuery()
            ->with([
                'client',
                'transports',
                'costLines',
                'revenueLines',
                'adjustmentLines',
            ])
            ->latest()
            ->get();
    }


    public function paginateWithClient(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->newQuery()
            ->with('client')
            ->latest()
            ->paginate($perPage);
    }

    public function loadFinancials(Job $job): Job
    {
        return $job->load([
            'transports',
            'costLines',
            'revenueLines',
            'adjustmentLines',
        ]);
    }

    public function findLastByReferencePrefix(string $prefix): ?Job
    {
        return $this->model
            ->newQuery()
            ->where('job_reference', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->first();
    }

    public function findByReference(string $reference): ?Job
    {
        return $this->model
            ->newQuery()
            ->where('job_reference', $reference)
            ->with([
                'client',
                'transports',
                'payments',
                'revenueLines',
            ])
            ->first();
    }


}
