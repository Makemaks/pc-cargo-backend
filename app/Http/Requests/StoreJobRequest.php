<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\JobStatus;

class StoreJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validate job creation only.
     * Related data (transports, costs, revenue)
     * are handled by their own endpoints.
     */
    public function rules(): array
    {
        return [
            'client_id' => [
                'required',
                'integer',
                'exists:clients,id',
            ],

            'status' => [
                'required',
                new Enum(JobStatus::class),
            ],

        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'Client is required.',
            'client_id.integer' => 'Client ID must be a valid integer.',
            'client_id.exists' => 'Selected client does not exist.',
        ];
    }
}
