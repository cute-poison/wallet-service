<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferFundsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'from_wallet_id'    => ['required', 'integer', 'exists:wallets,id'],
            'to_wallet_id'      => ['required', 'integer', 'exists:wallets,id', 'different:from_wallet_id'],
            'amount'            => ['required', 'numeric', 'gt:0'],
            'idempotency_key'   => ['required', 'string', 'max:255'],
        ];
    }
}
