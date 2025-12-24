<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\JobFinancialHelper;

class JobResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /**
         * Financial summary (pure helper, no DB calls)
         */
        $financials = JobFinancialHelper::totals($this->resource);

        /**
         * Primary transport (used for tables / listings)
         */
        $primaryTransport = $this->whenLoaded('transports')
            ? $this->transports->sortBy('sequence')->first()
            : null;

        return [
            /* =========================
             * Core Job
             * ========================= */
            'id' => $this->id,
            'job_reference' => $this->job_reference,

            'status' => $this->status?->value,

            'created_at' => $this->created_at?->toISOString(),
            'completed_at' => $this->completed_at?->toISOString(),

            /* =========================
             * Client
             * ========================= */
            'client' => $this->whenLoaded('client') ? [
                'id' => $this->client->id,
                'name' => $this->client->name,
            ] : null,

            /* =========================
             * Transport summary (lists)
             * ========================= */
            'transport' => $primaryTransport ? [
                'transport_mode' => $primaryTransport->transport_mode?->value,
                'origin' => $primaryTransport->origin,
                'destination' => $primaryTransport->destination,
                'status' => $primaryTransport->status?->value,
            ] : null,

            /* =========================
             * All transports (edit page)
             * ========================= */
            'transports' => $this->whenLoaded('transports')
                ? $this->transports
                    ->sortBy('sequence')
                    ->values()
                    ->map(fn ($t) => [
                        'id' => $t->id,
                        'sequence' => $t->sequence,
                        'transport_mode' => $t->transport_mode?->value,
                        'origin' => $t->origin,
                        'destination' => $t->destination,
                        'status' => $t->status?->value,
                        'created_at' => $t->created_at?->toISOString(),
                    ])
                : [],

            /* =========================
             * Financial totals
             * ========================= */
            'financials' => [
                'total_costs' => $financials['total_costs'],
                'total_revenue' => $financials['total_revenue'],
                'total_adjustments' => $financials['total_adjustments'],
                'gross_profit' => $financials['gross_profit'],
            ],

            /* =========================
             * Financial lines (edit/view)
             * ========================= */
            'costs' => JobCostLineResource::collection(
                $this->whenLoaded('costLines')
            ),

            'revenues' => JobRevenueLineResource::collection(
                $this->whenLoaded('revenueLines')
            ),

            'adjustments' => JobAdjustmentLineResource::collection(
                $this->whenLoaded('adjustmentLines')
            ),
        ];
    }
}
