<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('wallet_id');
    $table->unsignedBigInteger('counterparty_wallet_id');
    $table->enum('type', ['credit', 'debit']);
    $table->decimal('amount', 20, 8);
    $table->string('idempotency_key')->unique();
    $table->timestamps();

    $table->foreign('wallet_id')
          ->references('id')
          ->on('wallets')
          ->cascadeOnDelete();

    $table->foreign('counterparty_wallet_id')
          ->references('id')
          ->on('wallets')
          ->cascadeOnDelete();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
