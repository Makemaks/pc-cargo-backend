<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Services\JobTransportService;
use App\Http\Requests\StoreJobTransportRequest;
use App\Http\Resources\JobTransportResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class JobTransportController extends Controller
{
    protected JobTransportService $service;

    public function __construct(JobTransportService $service)
    {
        $this->service = $service;
    }

    public function index(Job $job): ResourceCollection
    {
        return JobTransportResource::collection(
            $this->service->listByJob($job)
        );
    }

    public function store(Job $job, StoreJobTransportRequest $request): JobTransportResource
    {
        $transport = $this->service->createForJob($job, $request->validated());

        return new JobTransportResource($transport);
    }

    public function update(
        Job $job,
        int $id,
        StoreJobTransportRequest $request
    ): JobTransportResource {
        $transport = $this->service->updateTransport(
            $id,
            $job,
            $request->validated()
        );

        return new JobTransportResource($transport);
    }

    public function destroy(Job $job, int $id)
    {
        $this->service->deleteTransport($id, $job);

        return response()->noContent();
    }
}
