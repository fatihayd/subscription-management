<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionStoreRequest;
use App\Http\Requests\SubscriptionUpdateRequest;
use App\Http\Requests\TransactionStoreRequest;
use App\Services\SubscriptionService;
use App\Services\TransactionService;
use Illuminate\Http\Response;

class UserController extends Controller
{
    protected SubscriptionService $subscriptionService;
    protected TransactionService $transactionService;


    public function __construct(SubscriptionService $subscriptionService, TransactionService $transactionService)
    {
        $this->subscriptionService = $subscriptionService;
        $this->transactionService = $transactionService;
    }

    public function addSubscription(int $userId,SubscriptionStoreRequest $request):Response
    {
        return $this->subscriptionService->subscribe($userId, $request->validated());
    }

    public function updateSubscription(int $userId, int $subscriptionId, SubscriptionUpdateRequest $request):Response
    {
        return $this->subscriptionService->updateSubscription($userId, $subscriptionId, $request->validated());
    }

    public function deleteSubscription(int $userId, int $subscriptionId):Response
    {
        return $this->subscriptionService->deleteSubscription($userId, $subscriptionId);
    }

    public function deleteSubscriptions($userId):Response
    {
        return $this->subscriptionService->deleteSubscriptions($userId);
    }

    public function addTransaction($userId, TransactionStoreRequest $request):Response
    {
        return $this->transactionService->addTransaction($userId, $request->subscription_id);
    }

    public function getSubscriptionAndTransactions($userId):Response
    {
        return $this->subscriptionService->getSubscriptionAndTransactions($userId);
    }

}
