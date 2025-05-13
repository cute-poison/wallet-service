<?php

use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    // Wallet endpoints
    Route::post('wallets', [WalletController::class, 'store']);
    Route::get('wallets/{id}', [WalletController::class, 'show']);
    Route::post('wallets/transfer', [WalletController::class, 'transfer']);

    // Transaction history
    Route::get('wallets/{walletId}/transactions', [TransactionController::class, 'index']);
});


// <?php

// use App\Http\Controllers\WalletController;
// use App\Http\Controllers\TransactionController;
// use Illuminate\Support\Facades\Route;

// Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
//     Route::post('wallets', [WalletController::class, 'store']);
//     Route::get('wallets/{id}', [WalletController::class, 'show']);
//     Route::post('wallets/transfer', [WalletController::class, 'transfer']);
//     Route::get('wallets/{walletId}/transactions', [TransactionController::class, 'index']);
// });

// // Public endpoint to download the Swagger JSON
// Route::get('api-docs.json', function () {
//     $file = storage_path('api-docs/api-docs.json');

//     if (! file_exists($file)) {
//         abort(404, 'OpenAPI spec not found. Run php artisan l5-swagger:generate');
//     }

//     return response()->download(
//         $file,
//         'api-docs.json',
//         ['Content-Type' => 'application/json']
//     );
// });
