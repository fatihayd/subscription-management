<?php

namespace App\Http\Requests;

use App\Rules\DateBetweenOneMonth;
use Illuminate\Foundation\Http\FormRequest;

class SubscriptionStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'renewed_at' => ['required', 'date_format:Y-m-d'],
            'expired_at' => ['required', 'date_format:Y-m-d', new DateBetweenOneMonth]
        ];
    }
}
