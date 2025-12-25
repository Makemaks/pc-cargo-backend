<?php

namespace App\Services;

use App\Models\Job;
use App\Models\JobPayment;
use App\Enums\JobStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

class PaymentService extends BaseService
{
    protected PaymentRepositoryInterface $repository;

    public function __construct(PaymentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    protected function repository(): PaymentRepositoryInterface
    {
        return $this->repository;
    }

    /**
     * ==========================
     * Create Payment Order
     * ==========================
     */
    public function createForJob(Job $job, string $provider): JobPayment
    {
        $this->guardJobPayable($job);

        return match ($provider) {
            'paypal' => $this->createPaypalOrder($job),
            'stripe' => $this->createStripeOrder($job),
            default  => throw new RuntimeException('Unsupported payment provider.'),
        };
    }

    /**
     * ==========================
     * PayPal Implementation
     * ==========================
     */
    protected function createPaypalOrder(Job $job): JobPayment
    {
        // ✅ Ensure revenues are loaded
        $job->loadMissing('revenueLines');

        // ✅ Payment amount = total revenue
        $amount = $job->revenueLines->sum('amount');
        $currency = $job->currency ?? 'USD';

        if ($amount <= 0) {
            throw new RuntimeException('Invalid payment amount.');
        }

        $accessToken = $this->getPaypalAccessToken();

        /** @var Response $response */
        $response = Http::withToken($accessToken)->post(
            config('services.paypal.base_url') . '/v2/checkout/orders',
            [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => $job->job_reference,
                    'amount' => [
                        'currency_code' => $currency,
                        'value' => number_format($amount, 2, '.', ''),
                    ],
                ]],
            ]
        );

        if (! $response->successful()) {
            throw new RuntimeException('Failed to create PayPal order.');
        }

        $externalReference = $response->json('id');

        if (! $externalReference) {
            throw new RuntimeException('Invalid PayPal response.');
        }

        return $this->repository->create([
            'job_id'             => $job->id,
            'payment_method'     => PaymentMethod::PayPal,
            'amount'             => $amount,
            'currency'           => $currency,
            'external_reference' => $externalReference,
            'status'             => PaymentStatus::Pending,
            'received_at'        => null,
        ]);
    }


    /**
     * ==========================
     * Stripe (Not Implemented)
     * ==========================
     */
    protected function createStripeOrder(Job $job): JobPayment
    {
        throw new RuntimeException('Stripe payment is not yet implemented.');
    }

    /**
     * ==========================
     * Guards
     * ==========================
     */
    protected function guardJobPayable(Job $job): void
    {
        if ($job->status !== JobStatus::Completed) {
            throw new RuntimeException('Job is not payable.');
        }

        if ($job->is_paid) {
            throw new RuntimeException('Job is already paid.');
        }
    }

    /**
     * ==========================
     * PayPal Auth
     * ==========================
     */
    protected function getPaypalAccessToken(): string
    {
        return Cache::remember('paypal_access_token', 50 * 60, function () {
            /** @var Response $response */
            $response = Http::asForm()
                ->withBasicAuth(
                    config('services.paypal.client_id'),
                    config('services.paypal.secret')
                )
                ->post(
                    config('services.paypal.base_url') . '/v1/oauth2/token',
                    ['grant_type' => 'client_credentials']
                );

            if (! $response->successful()) {
                throw new RuntimeException('Failed to authenticate with PayPal.');
            }

            return $response->json('access_token');
        });
    }

    public function captureForJob(
        Job $job,
        string $provider,
        string $orderId
    ): string {
        return match ($provider) {
            'paypal' => $this->capturePaypalOrder($job, $orderId),
            default  => throw new RuntimeException('Unsupported payment provider.'),
        };
    }

    protected function capturePaypalOrder(
        Job $job,
        string $orderId
    ): string {
        $this->guardJobPayable($job);

        // Find pending payment
        $payment = $this->repository->findLatestByJob($job->id);

        if (! $payment) {
            throw new RuntimeException('Payment record not found.');
        }

        if ($payment->external_reference !== $orderId) {
            throw new RuntimeException('Order ID mismatch.');
        }

        if ($payment->status !== PaymentStatus::Pending) {
            throw new RuntimeException('Payment is not pending.');
        }

        $accessToken = $this->getPaypalAccessToken();

        /** @var \Illuminate\Http\Client\Response $response */
        $response = Http::withToken($accessToken)
            ->asJson()
            ->post(
                config('services.paypal.base_url')
                . "/v2/checkout/orders/{$orderId}/capture",
                new \stdClass()
            );

        if (! $response->successful()) {

            logger()->error('PayPal capture failed', [
                'status' => $response->status(),
                'body'   => $response->json(),
            ]);

            $this->repository->update($payment, [
                'status' => PaymentStatus::Failed,
            ]);

            return 'failed';
        }

        if ($response->json('status') !== 'COMPLETED') {
            throw new RuntimeException('PayPal capture not completed.');
        }


        // Optional: validate amount from PayPal response
        $capturedAmount = $response->json(
            'purchase_units.0.payments.captures.0.amount.value'
        );

        if ((float) $capturedAmount !== (float) $payment->amount) {
            throw new RuntimeException('Captured amount mismatch.');
        }

        // Mark payment as paid
        $this->repository->update($payment, [
            'status'      => PaymentStatus::Paid,
            'received_at' => now(),
        ]);

        // Mark job as paid (domain rule)
        $job->update([
            'is_paid' => true,
        ]);

        return 'paid';
    }


    }
