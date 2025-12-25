<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected PaymentService $service;

    public function __construct(PaymentService $service)
    {
        $this->service = $service;
    }

    /**
     * POST /jobs/{job}/payments/{provider}/order
     */
    public function createOrder(Job $job, string $provider): JsonResponse
    {
        $payment = $this->service->createForJob($job, $provider);

        return response()->json([
            'order_id' => $payment->external_reference,
        ]);
    }

    /**
     * POST /jobs/{job}/payments/{provider}/capture
     */
    public function capture(
        Job $job,
        string $provider,
        Request $request
    ): JsonResponse {
        $request->validate([
            'order_id' => ['required', 'string'],
        ]);

        $status = $this->service->captureForJob(
            job: $job,
            provider: $provider,
            orderId: $request->string('order_id')
        );

        return response()->json([
            'status' => $status,
        ]);
    }
}
