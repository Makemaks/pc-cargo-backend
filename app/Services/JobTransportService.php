<?php

namespace App\Services;

use App\Models\Job;
use App\Models\JobTransport;
use App\Enums\JobStatus;
use App\Repositories\Contracts\JobTransportRepositoryInterface;
use Illuminate\Support\Collection;
use RuntimeException;

class JobTransportService extends BaseService
{
    protected JobTransportRepositoryInterface $repository;

    public function __construct(JobTransportRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    protected function repository(): JobTransportRepositoryInterface
    {
        return $this->repository;
    }

    public function listByJob(Job $job): Collection
    {
        return $this->repository->getByJob($job->id);
    }

    public function createForJob(Job $job, array $data): JobTransport
    {
        $this->guardJobEditable($job);

        return $this->repository->create([
            'job_id' => $job->id,
            'transport_mode' => $data['transport_mode'],
            'origin' => $data['origin'],
            'destination' => $data['destination'],
            'sequence' => $data['sequence']
                ?? ($job->transports()->max('sequence') + 1),
        ]);
    }

    public function updateTransport(int $id, Job $job, array $data): JobTransport
    {
        $this->guardJobEditable($job);

        $transport = $this->findOrFail($id);

        return $this->repository->update($transport, $data);
    }

    public function deleteTransport(int $id, Job $job): bool
    {
        $this->guardJobEditable($job);

        $transport = $this->findOrFail($id);

        return $this->repository->delete($transport);
    }

    protected function guardJobEditable(Job $job): void
    {
        if ($job->status === JobStatus::Completed) {
            throw new RuntimeException('Transports cannot be modified after job completion.');
        }
    }
}
