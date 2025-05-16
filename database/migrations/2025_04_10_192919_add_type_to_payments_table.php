<?php

use App\Models\Bank;
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
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['payer_id']);
            $table->foreignIdFor(\App\Models\User::class, 'payer_id')->nullable()->change();
            $table->foreign('payer_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->tinyInteger('type')->default(1)->after('payer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['payer_id']);
            $table->foreignIdFor(\App\Models\User::class, 'payer_id')->nullable(false)->change();
            $table->foreign('payer_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->dropColumn('type');
        });
    }
};
