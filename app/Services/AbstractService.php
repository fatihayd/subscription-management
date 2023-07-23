<?php

namespace App\Services;

use Illuminate\Http\Response;

class AbstractService
{
    protected function formatResponse(array $responseArray,int $statusCode):array|Response
    {
        if(app()->runningInConsole() && !app()->runningUnitTests()){
            $responseArray['status'] = $statusCode >= 200 && $statusCode < 207;
            return $responseArray;
        }

        return response($responseArray,$statusCode);
    }

    protected function error_when_processing():array
    {
        return [
            ['message' => trans('messages.error_when_processing')],
            500
        ];
    }

    protected function error_user_not_found():array
    {
        return [
            ['message' => trans('messages.user_not_found')],
            404
        ];
    }

    protected function error_subscription_not_found():array
    {
        return [
            ['message' => trans('messages.subscription_not_found')],
            404
        ];
    }


}
