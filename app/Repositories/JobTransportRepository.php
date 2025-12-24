<?php

namespace App\Repositories;

use App\Models\JobTransport;
use App\Repositories\Contracts\JobTransportRepositoryInterface;
use Illuminate\Support\Collection;

class JobTransportRepository implements JobTransportRepositoryInterface
{
    public function create(array $data): JobTransport
    {
        return JobTransport::create($data);
    }

    public function update(JobTransport $transport, array $data): JobTransport
    {
        $transport->update($data);
        return $transport;
    }

    public function delete(JobTransport $transport): bool
    {
        return (bool) $transport->delete();
    }

    public function getByJob(int $jobId): Collection
    {
        return JobTransport::where('job_id', $jobId)
            ->orderBy('sequence')
            ->get();
    }

    public function find(int $id): ?JobTransport
    {
        return JobTransport::find($id);
    }
}
