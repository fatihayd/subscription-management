<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;

class DateBetweenOneMonth implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $renewedAt = request()->input('renewed_at');

        $renewedDate = Carbon::parse($renewedAt);
        $expiredDate = Carbon::parse($value);

        if(Carbon::canBeCreatedFromFormat($renewedAt,'Y-m-d')){
            if($expiredDate->diffInMonths($renewedDate) !== 1){
                $dataCanBe = $renewedDate->addMonth()->format('Y-m-d');
                $fail(trans('messages.expired_at_range_error',[
                    'attribute' => $attribute,
                    'date_can_be' => $dataCanBe,
                ]));
            }
        }

    }
}
