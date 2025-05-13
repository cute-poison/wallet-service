<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
       Schema::create('wallets', function (Blueprint $table) {
    $table->id();                              // BIGINT unsigned
    $table->unsignedBigInteger('user_id');
    $table->string('currency', 3);
    $table->decimal('balance', 20, 8)->default(0);
    $table->timestamps();

    $table->foreign('user_id')
          ->references('id')
          ->on('users')
          ->cascadeOnDelete();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
