<?php

namespace App\Repositories\Eloquent;

use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function create(array $data): Transaction
    {
        return Transaction::create($data);
    }

    public function findByIdempotencyKey(string $key): ?Transaction
    {
        return Transaction::where('idempotency_key', $key)->first();
    }

    public function getWalletTransactions(int $walletId, int $perPage = 15): LengthAwarePaginator
    {
        return Transaction::where('wallet_id', $walletId)
                          ->orderByDesc('created_at')
                          ->paginate($perPage);
    }
}
