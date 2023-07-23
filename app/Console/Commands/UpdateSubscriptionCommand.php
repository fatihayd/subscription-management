<?php

namespace App\Console\Commands;

use App\Services\SubscriptionService;
use App\Traits\CustomValidatorTrait;
use Illuminate\Console\Command;

class UpdateSubscriptionCommand extends Command
{
    use CustomValidatorTrait;

    protected $signature = 'update:subscription {--user_id=} {--subscription_id=} {--renewed_at=} {--expired_at=}';

    protected $description = 'Command description';

    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        parent::__construct();
        $this->subscriptionService = $subscriptionService;
    }

    public function handle()
    {
        $subscriptionId = $this->option('subscription_id');
        $userId = $this->option('user_id');
        $renewed_at = $this->option('renewed_at');
        $expired_at = $this->option('expired_at');

        if (!$userId) {
            $this->error(trans('messages.field_is_required', ['field' => 'user_id']));
            return;
        }
        if (!$subscriptionId) {
            $this->error(trans('messages.field_is_required', ['field' => 'subscription_id']));
            return;
        }

        $subscriptionData = [];
        if ($renewed_at) {
            $subscriptionData['renewed_at'] = $renewed_at;
        }
        if ($expired_at) {
            $subscriptionData['expired_at'] = $expired_at;
        }
        $validator = $this->validate($subscriptionData, 'SubscriptionUpdateRequest');
        if ($validator['error']) {
            $this->error($validator['message']);
            return;
        }

        $response = $this->subscriptionService->updateSubscription($userId, $subscriptionId, $subscriptionData);

        if (isset($response['status']) && $response['status']) {
            $this->info(trans('messages.subscription_updated_successfully'));
            $this->info(json_encode($response));
        } else {
            $this->error($response['message']);
        }
    }
}
