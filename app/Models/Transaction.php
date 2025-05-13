<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * @OA\Schema(
 *     schema="Transaction",
 *     type="object",
 *     title="Transaction",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="wallet_id", type="integer", example=2),
 *     @OA\Property(property="amount", type="number", format="float", example=150.75),
 *     @OA\Property(property="type", type="string", example="debit"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
 * )
 */

class Transaction extends Model
{
    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'counterparty_wallet_id',
        'idempotency_key',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function counterparty(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'counterparty_wallet_id');
    }
}
