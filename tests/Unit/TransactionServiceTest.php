<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\WalletService;
use App\Services\TransactionService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected WalletService $walletService;
    protected TransactionService $txService;

    protected function setUp(): void
    {
        parent::setUp();

        // Create and authenticate a user
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $this->walletService = $this->app->make(WalletService::class);
        $this->txService     = $this->app->make(TransactionService::class);
    }

    /** @test */
    public function transactions_are_paginated_and_ordered_descending(): void
    {
        // Create two wallets in the same currency
        $a = $this->walletService->createWallet(['currency' => 'USD']);
        $b = $this->walletService->createWallet(['currency' => 'USD']);

        // Seed wallet A with a balance
        $a->balance = 150;
        $a->save();

        // Perform 5 sequential transfers (10, 20, 30, 40, 50)
        foreach ([10, 20, 30, 40, 50] as $i => $amount) {
            $key = "txn-{$i}";
            $this->walletService->transfer($a->id, $b->id, $amount, $key);
        }

        // Request page 1 with 2 items per page
        $paginated = $this->txService->listForWallet($a->id, 2);

        // Assert we get exactly 2 items
        $this->assertCount(2, $paginated->items());

        // Assert total records ≥ 5
        $this->assertTrue($paginated->total() >= 5);

        // Assert ordering: first is newer than second
        $items = $paginated->items();
        $this->assertTrue($items[0]->created_at >= $items[1]->created_at);
    }
}
