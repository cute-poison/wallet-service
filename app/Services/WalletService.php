<?php

namespace App\Services;

use App\Models\Wallet;
use App\Repositories\Contracts\WalletRepositoryInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class WalletService
{
    public function __construct(
        private WalletRepositoryInterface      $walletRepo,
        private TransactionRepositoryInterface $txRepo,
        private DatabaseManager                $db
    ) {}

    /**
     * Create a new wallet for the authenticated user.
     */
    public function createWallet(array $data): Wallet
    {
        $data['user_id'] = auth()->id();
        $data['balance'] = 0;
        return $this->walletRepo->create($data);
    }

    /**
     * Retrieve a wallet by ID, ensuring it belongs to the user.
     */
    public function getWallet(int $id): Wallet
    {
        $wallet = $this->walletRepo->findById($id);
        if (! $wallet || $wallet->user_id !== auth()->id()) {
            throw new ModelNotFoundException("Wallet not found");
        }
        return $wallet;
    }

    /**
     * Transfer funds from one wallet to another atomically, with idempotency.
     *
     * @throws InvalidArgumentException if currencies mismatch or insufficient balance.
     */
    public function transfer(
        int    $fromWalletId,
        int    $toWalletId,
        float  $amount,
        string $idempotencyKey
    ): Wallet {
        // Idempotency: return existing tx if seen
        if ($existing = $this->txRepo->findByIdempotencyKey($idempotencyKey)) {
            return $this->getWallet($existing->wallet_id);
        }

        // Load and authorize wallets
        $from = $this->getWallet($fromWalletId);
        $to   = $this->walletRepo->findById($toWalletId);
        if (! $to) {
            throw new ModelNotFoundException("Destination wallet not found");
        }
        if ($from->currency !== $to->currency) {
            throw new InvalidArgumentException("Currency mismatch");
        }
        if ($from->balance < $amount) {
            throw new InvalidArgumentException("Insufficient funds");
        }

        // Atomic DB transaction
        return DB::transaction(function () use ($from, $to, $amount, $idempotencyKey) {
            // Debit sender
            $this->walletRepo->updateBalance($from, -1 * $amount);
            $this->txRepo->create([
                'wallet_id'              => $from->id,
                'counterparty_wallet_id' => $to->id,
                'type'                   => 'debit',
                'amount'                 => $amount,
                'idempotency_key'        => $idempotencyKey,
            ]);

            // Credit recipient
            $this->walletRepo->updateBalance($to, $amount);
            $this->txRepo->create([
                'wallet_id'              => $to->id,
                'counterparty_wallet_id' => $from->id,
                'type'                   => 'credit',
                'amount'                 => $amount,
                'idempotency_key'        => $idempotencyKey . '_rev',
            ]);

            return $this->getWallet($from->id);
        });
    }
}
