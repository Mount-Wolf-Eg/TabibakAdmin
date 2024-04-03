<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->float('weight')->after('social_status')->nullable();
            $table->float('height')->after('weight')->nullable();
            $table->tinyInteger('blood_type')->after('height')->nullable();
            $table->text('other_diseases')->after('blood_type')->nullable();
            $table->text('latest_surgeries')->after('other_diseases')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('weight');
            $table->dropColumn('height');
            $table->dropColumn('blood_type');
            $table->dropColumn('other_diseases');
            $table->dropColumn('latest_surgeries');
        });
    }
};
