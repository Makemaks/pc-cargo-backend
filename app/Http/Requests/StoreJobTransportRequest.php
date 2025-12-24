<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\TransportMode;

class StoreJobTransportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'transport_mode' => [
                'required',
                new Enum(TransportMode::class),
            ],
            'origin' => [
                'required',
                'string',
                'max:255',
            ],
            'destination' => [
                'required',
                'string',
                'max:255',
            ],
            'sequence' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'transport_mode.required' => 'Transport mode is required.',
            'origin.required' => 'Origin is required.',
            'destination.required' => 'Destination is required.',
        ];
    }
}
