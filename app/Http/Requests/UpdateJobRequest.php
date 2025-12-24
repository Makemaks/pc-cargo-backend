<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\JobStatus;

class UpdateJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validate JOB fields only
     */
    public function rules(): array
    {
        return [
            'client_id' => [
                'sometimes',
                'integer',
                'exists:clients,id',
            ],

            'status' => [
                'sometimes',
                new Enum(JobStatus::class),
            ],

            'currency' => [
                'sometimes',
                'string',
                'size:3',
            ],
        ];
    }
}
