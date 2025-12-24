<?php

namespace App\Repositories\Contracts;

use App\Models\JobTransport;
use Illuminate\Support\Collection;

interface JobTransportRepositoryInterface
{
    public function create(array $data): JobTransport;

    public function update(JobTransport $transport, array $data): JobTransport;

    public function delete(JobTransport $transport): bool;

    public function getByJob(int $jobId): Collection;

    public function find(int $id): ?JobTransport;
}
