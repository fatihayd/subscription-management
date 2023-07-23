<?php

namespace Tests\Unit;

use App\Models\Setting;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker;

    public function test_can_subscription_added_to_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson("/api/user/{$user->id}/subscription", [
                'renewed_at' => now()->format('Y-m-d'),
                'expired_at' => now()->addMonth()->format('Y-m-d'),
            ]);
        $user->delete();
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'user_id',
            "renewed_at",
            "expired_at",
            "updated_at",
            "created_at",
            "id"
        ]);

    }

    public function test_can_subscription_dates_update(): void
    {
        $user = User::factory()->create();
        $subscription = Subscription::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)
            ->putJson("/api/user/{$user->id}/subscription/{$subscription->id}", [
                'renewed_at' => now()->addDay()->format('Y-m-d'),
                'expired_at' => now()->addDay()->addMonth()->format('Y-m-d'),
            ]);

        $user->delete();
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'user_id',
            "renewed_at",
            "expired_at",
            "updated_at",
            "created_at",
        ]);
    }

    public function test_can_subscription_delete(): void
    {
        $user = User::factory()->create();
        $subscription = Subscription::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)
            ->delete("/api/user/{$user->id}/subscription/{$subscription->id}");

        $user->delete();
        $response->assertStatus(204);
    }

    public function test_can_subscriptions_delete(): void
    {
        $user = User::factory()->create();
        Subscription::factory()->create(['user_id' => $user->id]);
        Subscription::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->delete("/api/user/{$user->id}/subscriptions");

        $user->delete();
        $response->assertStatus(204);
    }

    public function test_can_transaction_added_to_subscription(): void
    {
        $user = User::factory()->create();
        $subscription = Subscription::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)
            ->postJson("/api/user/{$user->id}/transaction/",[
                'subscription_id' => $subscription->id
            ]);

        $user->delete();
        $response->assertStatus(201);
        $response->assertJsonStructure([
            "price",
            "subscription_id",
            "updated_at",
            "created_at",
            "id"
        ]);
    }

    public function test_does_user_information_return_as_expected(): void
    {
        $user = User::factory()->create();
        $subscription = Subscription::factory()->create(['user_id' => $user->id]);
        $setting = Setting::where('type','price')->first();
        $subscription->transactions()->create(['price' => $setting->value ?? 0]);

        $response = $this->actingAs($user)->get("/api/user/{$user->id}");

        $user->delete();
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'subscriptions' => [
                [
                    'id',
                    'user_id',
                    'renewed_at',
                    'expired_at',
                    'created_at',
                    'updated_at',
                    "transactions" => [
                        [
                            'id',
                            'subscription_id',
                            'price',
                            'created_at',
                            'updated_at',
                        ]
                    ]
                ]
            ],
            'transactions' => [
                [
                    'id',
                    'subscription_id',
                    'price',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
    }
}
