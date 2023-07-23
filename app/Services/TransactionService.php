<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Subscription;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;

class TransactionService extends AbstractService
{
    public function addTransaction(int $userId, int $subscriptionId): Response|array
    {
        try {
            $user = User::find($userId);
            if (!$user) {
                [$responseArray, $statusCode] = $this->error_user_not_found();
            } else {
                $subscription = Subscription::query()
                    ->where('user_id', $userId)
                    ->where('id', $subscriptionId)
                    ->first();

                if (!$subscription) {
                    [$responseArray, $statusCode] = $this->error_subscription_not_found();
                } else {
                    $setting = Setting::firstWhere('type', 'price');
                    if (!$setting) {
                        $responseArray = [
                            'message' => trans('messages.price_not_found')
                        ];
                        $statusCode = 404;
                    } else {
                        $transaction = $subscription->transactions()->create(
                            [
                                'price' => $setting->value
                            ]
                        );

                        $subscription->update(['renewed_at' => now(), 'expired_at' => now()->addMonth()]);

                        $responseArray = $transaction->toArray();
                        $statusCode = 201;
                    }
                }
            }
        } catch (Exception $e) {
            [$responseArray, $statusCode] = $this->error_when_processing();
        }

        return $this->formatResponse($responseArray, $statusCode);
    }

}
