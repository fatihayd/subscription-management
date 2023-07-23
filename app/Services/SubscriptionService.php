<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class SubscriptionService extends AbstractService
{
    public function subscribe($userId, $subscriptionData): Response|array
    {
        try {
            $user = User::find($userId);
            if (!$user) {
                [$responseArray, $statusCode] = $this->error_user_not_found();
            } else {
                DB::beginTransaction();

                $setting = Setting::firstWhere('type', 'price');
                if (!$setting) {
                    $responseArray = [
                        'message' => trans('messages.price_not_found')
                    ];
                    $statusCode = 404;
                } else {
                    $subscription = Subscription::create(
                        array_merge(
                            ['user_id' => $userId],
                            $subscriptionData
                        )
                    );

                    $subscription->transactions()->create(
                        [
                            'price' => $setting->value
                        ]
                    );

                    $responseArray = $subscription->toArray();
                    $statusCode = 201;
                }
                DB::commit();
            }
        } catch (Exception $e) {
            [$responseArray, $statusCode] = $this->error_when_processing();

            DB::rollBack();
        }

        return $this->formatResponse($responseArray, $statusCode);
    }

    public function updateSubscription(int $userId, int $subscriptionId, array $updateData): Response|array
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
                    $subscription->update($updateData);
                    $responseArray = $subscription->toArray();
                    $statusCode = 200;
                }
            }
        } catch (Exception $e) {
            [$responseArray, $statusCode] = $this->error_when_processing();
        }

        return $this->formatResponse($responseArray, $statusCode);
    }

    public function deleteSubscription(int $userId, int $subscriptionId): Response|array
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
                    $subscription->delete();
                    $responseArray = [];
                    $statusCode = 204;
                }
            }
        } catch (Exception $e) {
            [$responseArray, $statusCode] = $this->error_when_processing();
        }

        return $this->formatResponse($responseArray, $statusCode);
    }

    public function deleteSubscriptions(int $userId): Response|array
    {
        try {
            $user = User::find($userId);
            if (!$user) {
                [$responseArray, $statusCode] = $this->error_user_not_found();
            } else {
                $subscriptions = Subscription::query()
                    ->where('user_id', $userId)
                    ->get();

                if (count($subscriptions) === 0) {
                    [$responseArray, $statusCode] = $this->error_subscription_not_found();
                } else {
                    Subscription::query()
                        ->where('user_id', $userId)
                        ->delete();
                    $responseArray = [];
                    $statusCode = 204;
                }
            }
        } catch (Exception $e) {
            [$responseArray, $statusCode] = $this->error_when_processing();
        }

        return $this->formatResponse($responseArray, $statusCode);
    }

    public function getSubscriptionAndTransactions($userId): Response
    {
        try {
            $user = User::find($userId);
            if (!$user) {
                [$responseArray, $statusCode] = $this->error_user_not_found();
            } else {
                $subscriptions = Subscription::with('transactions')
                    ->where('user_id', $userId)
                    ->orderByDesc('created_at')
                    ->get();

                $transactions = Transaction::query()
                    ->whereIn('subscription_id', $subscriptions->pluck('id'))
                    ->orderByDesc('created_at')
                    ->get();

                $responseArray = [
                    'subscriptions' => $subscriptions,
                    'transactions' => $transactions
                ];
                $statusCode = 200;
            }
        } catch (Exception $e) {
            [$responseArray, $statusCode] = $this->error_when_processing();
        }

        return $this->formatResponse($responseArray, $statusCode);
    }
}
