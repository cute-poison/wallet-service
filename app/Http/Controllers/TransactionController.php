<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaginateTransactionsRequest;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Transactions",
 *     description="Endpoints for listing wallet transaction history"
 * )
 */
class TransactionController extends Controller
{
    public function __construct(private TransactionService $transactionService) {}

    /**
     * List paginated transactions for a wallet.
     *
     * @OA\Get(
     *     path="/api/wallets/{walletId}/transactions",
     *     operationId="listTransactions",
     *     tags={"Transactions"},
     *     summary="Get paginated transaction history for a wallet",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="walletId",
     *         in="path",
     *         description="ID of the wallet",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of transactions per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of transactions",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/Transaction")
     *             ),
     *             @OA\Property(property="total", type="integer"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="current_page", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Wallet not found")
     * )
     */
    public function index(PaginateTransactionsRequest $request, int $walletId): JsonResponse
    {
        $transactions = $this->transactionService
                             ->listForWallet($walletId, $request->validatedPerPage());
        return response()->json($transactions);
    }
}
