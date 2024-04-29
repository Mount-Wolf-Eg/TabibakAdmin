<?php

use App\Constants\PaymentStatusConstants;
use App\Models\Currency;
use App\Models\User;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'payer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'beneficiary_id')->constrained('users')->cascadeOnDelete();
            $table->morphs('payable');
            $table->foreignIdFor(Currency::class)->constrained()->cascadeOnDelete();
            $table->string('transaction_id')->unique();
            $table->decimal('amount', 10);
            $table->tinyInteger('payment_method');
            $table->tinyInteger('status')->default(PaymentStatusConstants::PENDING->value);
            $table->json('metadata')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
