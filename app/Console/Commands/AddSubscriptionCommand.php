<?php

namespace App\Console\Commands;

use App\Services\SubscriptionService;
use App\Traits\CustomValidatorTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class AddSubscriptionCommand extends Command
{
    use CustomValidatorTrait;

    protected $signature = 'add:subscription {--user_id=} {--renewed_at=} {--expired_at=}';

    protected $description = 'This command add subscription to user.';

    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        parent::__construct();
        $this->subscriptionService = $subscriptionService;
    }

    public function handle()
    {
        $userId = $this->option('user_id');
        $renewed_at = $this->option('renewed_at');
        $expired_at = $this->option('expired_at');
        if (!$userId) {
            $this->error(trans('messages.field_is_required', ['field' => 'user_id']));
            return;
        }
        if (!$renewed_at) {
            $this->error(trans('messages.field_is_required', ['field' => 'renewed_at']));
            return;
        }
        if (!$expired_at) {
            $this->error(trans('messages.field_is_required', ['field' => 'expired_at']));
            return;
        }
        $validateDate = $this->validateDates($renewed_at, $expired_at);
        if (!$validateDate['status']) {
            $this->error($validateDate['message']);
            return;
        }

        $subscriptionData = [
            'renewed_at' => $renewed_at,
            'expired_at' => $expired_at,
        ];
        $response = $this->subscriptionService->subscribe($userId, $subscriptionData);

        if (isset($response['status']) && $response['status']) {
            $this->info(trans('messages.subscription_added_successfully', ['id' => $response['id']]));
            $this->info(json_encode($response));
        } else {
            $this->error($response['message']);
        }
    }

    private function validateDates($renewedAt, $expireAt): array
    {
        if (!Carbon::canBeCreatedFromFormat($renewedAt, 'Y-m-d')) {
            return [
                'status' => false,
                'message' => trans('messages.field_not_match_date_format', ['field' => 'renewed_at'])
            ];
        }
        if (!Carbon::canBeCreatedFromFormat($expireAt, 'Y-m-d')) {
            return [
                'status' => false,
                'message' => trans('messages.field_not_match_date_format', ['field' => 'expire_at'])
            ];
        }

        $renewedDate = Carbon::parse($renewedAt);
        $expiredDate = Carbon::parse($expireAt);

        if ($expiredDate->diffInMonths($renewedDate) !== 1) {
            $dataCanBe = $renewedDate->addMonth()->format('Y-m-d');
            return [
                'status' => false,
                'message' => trans('messages.expired_at_range_error', [
                    'attribute' => 'expired_at',
                    'date_can_be' => $dataCanBe,
                ])
            ];
        }

        return ['status' => true];
    }
}
