<?php

namespace App\Console\Commands;

use App\Services\SubscriptionService;
use App\Traits\CustomValidatorTrait;
use Illuminate\Console\Command;

class DeleteSubscriptionCommand extends Command
{
    protected $signature = 'delete:subscription {--user_id=} {--subscription_id=}';

    protected $description = 'This command delete subscription by given id';

    use CustomValidatorTrait;

    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        parent::__construct();
        $this->subscriptionService = $subscriptionService;
    }

    public function handle()
    {
        $userId = $this->option('user_id');
        $subscriptionId = $this->option('subscription_id');

        if (!$userId) {
            $this->error(trans('messages.field_is_required', ['field' => 'user_id']));
            return;
        }
        if (!$subscriptionId) {
            $this->error(trans('messages.field_is_required', ['field' => 'subscription_id']));
            return;
        }

        $response = $this->subscriptionService->deleteSubscription($userId, $subscriptionId);

        if (isset($response['status']) && $response['status']) {
            $this->info(trans('messages.subscription_deleted_successfully'));
            $this->info(json_encode($response));
        } else {
            $this->error($response['message']);
        }
    }
}
