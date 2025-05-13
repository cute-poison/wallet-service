<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WalletApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(); // seeds User and Wallets
        $this->user = User::first();
        Sanctum::actingAs($this->user, [], 'sanctum');
    }

    /** @test */
    public function can_retrieve_seeded_wallet_balance(): void
    {
        $wallet = $this->user->wallets()->first();

        $response = $this->getJson("/api/wallets/{$wallet->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'id'       => $wallet->id,
                     'currency' => $wallet->currency,
                     'balance'  => (string) $wallet->balance,
                 ]);
    }

    /** @test */
    public function can_transfer_between_seeded_wallets(): void
    {
        $wallets = $this->user->wallets()->take(2)->get();
        [$from, $to] = $wallets;

        $response = $this->postJson('/api/wallets/transfer', [
            'from_wallet_id'  => $from->id,
            'to_wallet_id'    => $to->id,
            'amount'          => 100,
            'idempotency_key' => 'feature-test-1',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['id', 'currency', 'balance']);

        // Confirm balances updated
        $this->assertDatabaseHas('wallets', [
            'id'      => $from->id,
            'balance' => number_format($from->balance - 100, 8, '.', ''),
        ]);
        $this->assertDatabaseHas('wallets', [
            'id'      => $to->id,
            'balance' => number_format($to->balance + 100, 8, '.', ''),
        ]);
    }
}
