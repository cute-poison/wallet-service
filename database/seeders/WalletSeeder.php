<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wallet;
use App\Models\User;

class WalletSeeder extends Seeder
{
    public function run(): void
    {
        // Grab or create the test user
        $user = User::firstOr(fn() => User::factory()->create());

        // Create two wallets for that user
       Wallet::factory()->count(2)->create([
    'user_id'  => $user->id,
    'currency' => 'USD',
    'balance'  => 1000,
]);

    }
}
