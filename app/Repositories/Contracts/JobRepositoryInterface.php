<?php

namespace App\Repositories\Contracts;

use App\Models\Job;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface JobRepositoryInterface extends BaseRepositoryInterface
{
    public function paginateWithClient(int $perPage = 15): LengthAwarePaginator;

    public function loadFinancials(Job $job): Job;

    public function findLastByReferencePrefix(string $prefix): ?Job;

    public function allWithFinancials(): Collection;

}
