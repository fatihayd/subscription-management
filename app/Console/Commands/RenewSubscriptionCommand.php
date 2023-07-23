<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\TransactionService;
use Illuminate\Console\Command;

class RenewSubscriptionCommand extends Command
{
    protected $signature = 'renew:subscription';

    protected $description = 'This command renew expired subscription. Will work every 23:55';

    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        parent::__construct();
        $this->transactionService = $transactionService;
    }

    public function handle()
    {

        $expiredSubscription = Subscription::with('user')
            ->where('expired_at','<=', now()->format('Y-m-d'))
            ->get();
        foreach ($expiredSubscription as $subscription) {
            $this->transactionService->addTransaction($subscription->user_id, $subscription->id);
            $this->info(trans('messages.subscription_renewed', [
                'name' => $subscription->user->name,
                'id' => $subscription->id
            ]));
        }
    }
}
