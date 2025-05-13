<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateWalletRequest;
use App\Http\Requests\TransferFundsRequest;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Wallets",
 *     description="Endpoints for creating and managing wallets"
 * )
 */
class WalletController extends Controller
{
    public function __construct(private WalletService $walletService) {}

    /**
     * Create a new wallet.
     *
     * @OA\Post(
     *     path="/api/wallets",
     *     operationId="createWallet",
     *     tags={"Wallets"},
     *     summary="Create a new wallet for the authenticated user",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"currency"},
     *             @OA\Property(property="currency", type="string", example="USD")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Wallet created",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="currency", type="string"),
     *             @OA\Property(property="balance", type="number", format="float")
     *         )
     *     )
     * )
     */
    public function store(CreateWalletRequest $request): JsonResponse
    {
        $wallet = $this->walletService->createWallet($request->validated());
        return response()->json($wallet, 201);
    }

    /**
     * Retrieve a wallet’s details and balance.
     *
     * @OA\Get(
     *     path="/api/wallets/{id}",
     *     operationId="getWallet",
     *     tags={"Wallets"},
     *     summary="Get wallet details by ID",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Wallet details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="currency", type="string"),
     *             @OA\Property(property="balance", type="number", format="float")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Wallet not found")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $wallet = $this->walletService->getWallet($id);
        return response()->json($wallet);
    }

    /**
     * Transfer funds between two wallets.
     *
     * @OA\Post(
     *     path="/api/wallets/transfer",
     *     operationId="transferFunds",
     *     tags={"Wallets"},
     *     summary="Transfer funds from one wallet to another",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"from_wallet_id","to_wallet_id","amount","idempotency_key"},
     *             @OA\Property(property="from_wallet_id", type="integer", example=1),
     *             @OA\Property(property="to_wallet_id", type="integer", example=2),
     *             @OA\Property(property="amount", type="number", format="float", example=50.00),
     *             @OA\Property(property="idempotency_key", type="string", example="unique-key-123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transfer successful; returns updated source wallet",
     *         @OA\JsonContent(ref="#/components/schemas/Wallet")
     *     ),
     *     @OA\Response(response=400, description="Validation or business rule error")
     * )
     */
    public function transfer(TransferFundsRequest $request): JsonResponse
    {
        $result = $this->walletService->transfer(
            $request->from_wallet_id,
            $request->to_wallet_id,
            $request->amount,
            $request->idempotency_key
        );
        return response()->json($result);
    }
}
