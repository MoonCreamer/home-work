<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('from_account')->constrained('bank_accounts')->onDelete('cascade');
            $table->foreignId('to_account')->constrained('bank_accounts')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
