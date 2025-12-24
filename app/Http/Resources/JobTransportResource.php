<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class JobTransportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'job_id' => $this->job_id,

            // SAFE enum serialization
            'transport_mode' => $this->transport_mode?->value,
            'status' => $this->status?->value,

            'origin' => $this->origin,
            'destination' => $this->destination,
            'sequence' => $this->sequence,
            'created_at' => $this->created_at?->toISOString(),
            'deleted_at' => $this->deleted_at?->toISOString(),
        ];

    }
}
