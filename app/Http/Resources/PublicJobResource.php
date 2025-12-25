<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicJobResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->job_reference,
            'status' => $this->status,

            /**
             * Payment state (single source of truth)
             */
            'is_paid' => $this->payments
                ->where('status', 'paid')
                ->isNotEmpty(),

            /**
             * PUBLIC PAYABLE AMOUNT
             * Derived from revenue (never from frontend)
             */
            'total_amount' => $this->revenueLines->sum('amount'),
            'currency' => $this->revenueLines->first()?->currency ?? 'USD',

            /**
             * Limited client info
             */
            'client' => $this->client
                ? [
                    'id' => $this->client->id,
                    'name' => $this->client->name,
                ]
                : null,

            /**
             * Transport legs
             */
            'transports' => $this->transports->map(fn ($t) => [
                'id' => $t->id,
                'sequence' => $t->sequence,
                'transportMode' => $t->transport_mode,
                'origin' => $t->origin,
                'destination' => $t->destination,
                'status' => $t->status,
                'createdAt' => $t->created_at?->toISOString(),
                'updatedAt' => $t->updated_at?->toISOString(),
            ]),

            /**
             * Payments (public summary only)
             */
            'payments' => $this->payments->map(fn ($p) => [
                'id' => $p->id,
                'method' => $p->payment_method->value,
                'status' => $p->status->value,
                'receivedAt' => $p->received_at?->toISOString(),
            ]),
        ];
    }
}
