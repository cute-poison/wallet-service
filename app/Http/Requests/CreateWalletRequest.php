<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateWalletRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only authenticated users:
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'currency' => ['required', 'string', 'size:3', 'alpha'],
        ];
    }
}
