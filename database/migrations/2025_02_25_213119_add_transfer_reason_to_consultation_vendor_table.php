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
        Schema::table('consultation_vendor', function (Blueprint $table) {
            $table->string('transfer_reason')->nullable()->after('consultation_id');
            $table->string('transfer_notes')->nullable()->after('transfer_reason');
            $table->string('transfer_case_rate')->nullable()->after('transfer_notes');
            $table->integer('type')->default(0)->after('transfer_case_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultation_vendor', function (Blueprint $table) {
            $table->dropColumn('transfer_reason');
            $table->dropColumn('transfer_notes');
            $table->dropColumn('transfer_case_rate');
            $table->dropColumn('type');
        });
    }
};
