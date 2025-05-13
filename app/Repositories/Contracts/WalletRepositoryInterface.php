<?php

namespace App\Repositories\Contracts;

use App\Models\Wallet;

interface WalletRepositoryInterface
{
    public function create(array $data): Wallet;
    public function findById(int $id): ?Wallet;
    public function findUserWallet(int $userId, int $walletId): ?Wallet;
    public function updateBalance(Wallet $wallet, float $amount): bool;
}
