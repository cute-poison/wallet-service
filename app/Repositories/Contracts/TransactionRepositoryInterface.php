<?php

namespace App\Repositories\Contracts;

use App\Models\Transaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TransactionRepositoryInterface
{
    public function create(array $data): Transaction;
    public function findByIdempotencyKey(string $key): ?Transaction;
    public function getWalletTransactions(int $walletId, int $perPage = 15): LengthAwarePaginator;
}
