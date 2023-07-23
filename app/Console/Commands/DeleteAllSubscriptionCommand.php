<?php

namespace App\Console\Commands;

use App\Services\SubscriptionService;
use App\Traits\CustomValidatorTrait;
use Illuminate\Console\Command;

class DeleteAllSubscriptionCommand extends Command
{
    protected $signature = 'delete:all_subscription {--user_id=}';

    protected $description = 'This command delete all subscription by given user_id';

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

        if (!$userId) {
            $this->error(trans('messages.field_is_required', ['field' => 'user_id']));
            return;
        }

        $response = $this->subscriptionService->deleteSubscriptions($userId);

        if (isset($response['status']) && $response['status']) {
            $this->info(trans('messages.subscriptions_deleted_successfully'));
            $this->info(json_encode($response));
        } else {
            $this->error($response['message']);
        }
    }
}
