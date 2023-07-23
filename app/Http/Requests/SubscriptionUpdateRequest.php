<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'renewed_at' => 'date_format:Y-m-d',
            'expired_at' => 'date_format:Y-m-d'
        ];
    }
}
