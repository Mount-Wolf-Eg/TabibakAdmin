<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn('other_diseases');
            $table->dropColumn('latest_surgeries');
        });
        Schema::drop('consultation_disease');
    }

    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->text('other_diseases')->after('prescription')->nullable();
            $table->text('latest_surgeries')->after('other_diseases')->nullable();
        });
        Schema::create('consultation_disease', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('disease_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }
};
