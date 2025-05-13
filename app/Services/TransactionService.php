<?php

namespace App\Services;

use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Repositories\Contracts\WalletRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TransactionService
{
    public function __construct(
        private TransactionRepositoryInterface $txRepo,
        private WalletRepositoryInterface      $walletRepo
    ) {}

    /**
     * List paginated transactions for a wallet, ensuring user ownership.
     *
     * @param  int  $walletId
     * @param  int  $perPage
     * @return LengthAwarePaginator
     *
     * @throws ModelNotFoundException
     */
    public function listForWallet(int $walletId, int $perPage = 15): LengthAwarePaginator
    {
        $wallet = $this->walletRepo->findById($walletId);
        if (! $wallet || $wallet->user_id !== auth()->id()) {
            throw new ModelNotFoundException("Wallet not found");
        }
        return $this->txRepo->getWalletTransactions($walletId, $perPage);
    }
}
