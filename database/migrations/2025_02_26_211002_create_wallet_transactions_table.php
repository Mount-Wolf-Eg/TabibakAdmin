<?php

use App\Models\User;
use App\Models\Wallet;
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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Wallet::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->nullableMorphs('modelable');
            $table->double('balance_before', 15, 2)->default(0)->nullable();
            $table->double('balance_after', 15, 2)->default(0)->nullable();
            $table->double('amount')->default(0)->nullable();
            $table->string('type'); // deposit - withdraw - transfer
            $table->string('status')->default('pending'); // pending - accepted - rejected - in_progress - completed
            $table->string('bank_name')->nullable();
            $table->string('iban')->nullable();
            $table->string('reference')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
