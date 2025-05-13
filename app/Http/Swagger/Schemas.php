<?php

namespace App\Http\Swagger;

/**
 * @OA\Schema(
 *   schema="Wallet",
 *   @OA\Property(property="id", type="integer"),
 *   @OA\Property(property="user_id", type="integer"),
 *   @OA\Property(property="currency", type="string"),
 *   @OA\Property(property="balance", type="number", format="float")
 * )
 */

/**
 * @OA\Schema(
 *   schema="Transaction",
 *   @OA\Property(property="id", type="integer"),
 *   @OA\Property(property="wallet_id", type="integer"),
 *   @OA\Property(property="type", type="string"),
 *   @OA\Property(property="amount", type="number", format="float"),
 *   @OA\Property(property="counterparty_wallet_id", type="integer"),
 *   @OA\Property(property="created_at", type="string", format="date-time")
 * )
 */
