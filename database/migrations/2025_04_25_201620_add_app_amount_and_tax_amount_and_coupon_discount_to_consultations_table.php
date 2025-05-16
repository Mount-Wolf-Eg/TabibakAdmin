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
        Schema::table('consultations', function (Blueprint $table) {
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete()->after('doctor_id')->comment('Coupon ID');
            $table->decimal('app_amount', 10, 2)->default(0)->after('amount')->comment('App amount');
            $table->decimal('tax_amount', 10, 2)->default(0)->after('app_amount')->comment('Tax amount');
            $table->decimal('coupon_discount', 10, 2)->default(0)->after('tax_amount')->comment('Coupon discount');
            $table->decimal('doctor_amount', 10, 2)->default(0)->after('coupon_discount')->comment('Doctor amount');
            $table->decimal('total_amount', 10, 2)->default(0)->after('coupon_discount')->comment('Total amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
            $table->dropColumn('coupon_id');
            $table->dropColumn('app_amount');
            $table->dropColumn('tax_amount');
            $table->dropColumn('coupon_discount');
            $table->dropColumn('total_amount');
        });
    }
};
