<?php

namespace App\Console\Commands;

use App\Services\TransactionService;
use App\Traits\CustomValidatorTrait;
use Illuminate\Console\Command;

class AddTransactionCommand extends Command
{
    use CustomValidatorTrait;

    protected $signature = 'add:transaction {--user_id=} {--subscription_id=}';

    protected $description = 'This command add transaction to given subscription';

    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        parent::__construct();
        $this->transactionService = $transactionService;
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
        $transactionData = [
            'subscription_id' => $subscriptionId
        ];
        $validator = $this->validate($transactionData, 'TransactionStoreRequest');
        if ($validator['error']) {
            $this->error($validator['message']);
            return;
        }

        $response = $this->transactionService->addTransaction($userId, $subscriptionId);

        if (isset($response['status']) && $response['status']) {
            $this->info(trans('messages.transaction_added_successfully'));
            $this->info(json_encode($response));
        } else {
            $this->error($response['message']);
        }
    }
}
