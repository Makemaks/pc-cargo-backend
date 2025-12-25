<?php

namespace App\Http\Controllers;

use App\Services\JobService;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Requests\UpdateJobStatusRequest;
use App\Http\Resources\JobResource;
use App\Http\Resources\PublicJobResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\ResourceCollection;

class JobController extends Controller
{
    protected JobService $service;

    public function __construct(JobService $service)
    {
        $this->service = $service;
    }

    /**
     * List jobs.
     */
    public function index()
    {
        return JobResource::collection(
            $this->service->allWithFinancials()
        );
    }

    /**
     * Create a job.
     */
    public function store(StoreJobRequest $request): JobResource
    {
        $job = $this->service->create($request->validated());

        return new JobResource($job);
    }

    /**
     * Show job with details and financials.
     */
    public function show(int $id): JobResource
    {
        $job = $this->service->getWithFinancials($id);

        return new JobResource($job);
    }

    /**
     * Update job info.
     */
    public function update(UpdateJobRequest $request, int $id): JobResource
    {
        $this->service->update($id, $request->validated());

        $job = $this->service->getWithFinancials($id);

        return new JobResource($job);
    }


    /**
     * Update job status.
     */
    public function updateStatus(UpdateJobStatusRequest $request, int $id): JobResource
    {
        $job = $this->service->updateStatus(
            $id,
            $request->validated()['status']
        );

        return new JobResource($job);
    }

    /**
     * Delete a job with its relations.
     */
    public function destroy(int $id)
    {
        $this->service->delete($id);

        return response()->json([
            'message' => 'Job deleted successfully.',
        ], 204);
    }

    /**
     * Show job by reference (PUBLIC).
     */
    public function showByReference(string $reference): PublicJobResource
    {
        $job = $this->service->getByReference($reference);

        return new PublicJobResource($job);
    }

}
