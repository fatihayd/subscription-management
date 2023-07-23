<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait CustomValidatorTrait
{
    public function validate(array $data, string $requestName):array
    {
        $requestClass = $this->getRequestClass($requestName);
        $validator = Validator::make($data, (new $requestClass)->rules());
        if ($validator->fails()) {
            return [
                'error' => true,
                'message' => $validator->errors()->first()
            ];
        }
        return ['error' => false];
    }

    private function getRequestClass(string $requestName):string
    {
        return "\\App\\Http\\Requests\\{$requestName}";
    }
}
