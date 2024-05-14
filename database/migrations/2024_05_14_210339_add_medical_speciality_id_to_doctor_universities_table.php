<?php

use App\Models\MedicalSpeciality;
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
        Schema::table('doctor_universities', function (Blueprint $table) {
            $table->foreignIdFor(MedicalSpeciality::class)->nullable()
                ->after('academic_degree_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_universities', function (Blueprint $table) {
            $table->dropForeign(['medical_speciality_id']);
            $table->dropColumn('medical_speciality_id');
        });
    }
};
