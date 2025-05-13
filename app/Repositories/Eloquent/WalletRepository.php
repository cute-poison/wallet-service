<?php

namespace App\Repositories\Eloquent;

use App\Models\Wallet;
use App\Repositories\Contracts\WalletRepositoryInterface;

class WalletRepository implements WalletRepositoryInterface
{
    public function create(array $data): Wallet
    {
        return Wallet::create($data);
    }

    public function findById(int $id): ?Wallet
    {
        return Wallet::find($id);
    }

    public function findUserWallet(int $userId, int $walletId): ?Wallet
    {
        return Wallet::where('id', $walletId)
                     ->where('user_id', $userId)
                     ->first();
    }

    public function updateBalance(Wallet $wallet, float $amount): bool
    {
        $wallet->balance = $wallet->balance + $amount;
        return $wallet->save();
    }
}
