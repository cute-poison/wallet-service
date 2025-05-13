<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\WalletService;
use App\Models\User;
use App\Repositories\Eloquent\WalletRepository;
use App\Repositories\Eloquent\TransactionRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\DatabaseManager;
use InvalidArgumentException;

class WalletServiceTest extends TestCase
{
    use RefreshDatabase;

    private WalletService $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        // Bind real implementations
        $this->service = $this->app->make(WalletService::class);
    }

    /** @test */
    public function it_initializes_a_new_wallet_with_zero_balance(): void
    {
        $wallet = $this->service->createWallet(['currency' => 'USD']);
        $this->assertEquals(0, $wallet->balance);
        $this->assertEquals('USD', $wallet->currency);
        $this->assertEquals(auth()->id(), $wallet->user_id);
    }

    /** @test */
    public function it_throws_if_transfer_insufficient_funds(): void
    {
        $from = $this->service->createWallet(['currency' => 'USD']);
        $to   = $this->service->createWallet(['currency' => 'USD']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Insufficient funds');

        $this->service->transfer(
            $from->id,
            $to->id,
            100.00,
            'key-123'
        );
    }
}
