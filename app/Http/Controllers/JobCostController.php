<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Services\JobCostService;
use App\Http\Requests\StoreJobCostRequest;
use App\Http\Requests\UpdateJobCostRequest;
use App\Http\Resources\JobCostLineResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\JsonResponse;

class JobCostController extends Controller
{
    protected JobCostService $service;

    public function __construct(JobCostService $service)
    {
        $this->service = $service;
    }

    public function index(Job $job): ResourceCollection
    {
        return JobCostLineResource::collection(
            $this->service->listByJob($job)
        );
    }

    public function store(StoreJobCostRequest $request, Job $job): JobCostLineResource
    {
        $cost = $this->service->createForJob($job, $request->validated());

        return new JobCostLineResource($cost);
    }

    public function update(UpdateJobCostRequest $request, Job $job, int $id): JobCostLineResource
    {
        $cost = $this->service->updateCost($id, $job, $request->validated());

        return new JobCostLineResource($cost);
    }

    public function destroy(Job $job, int $id): JsonResponse
    {
        $this->service->deleteCost($id, $job);

        return response()->json(null, 204);
    }
}
