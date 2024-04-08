<?php

use App\Constants\ConsultationPatientStatusConstants;
use App\Models\Consultation;
use App\Models\Doctor;
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
        Schema::create('consultation_doctor_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Consultation::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Doctor::class)->constrained()->cascadeOnDelete();
            $table->dateTime('doctor_set_consultation_at');
            $table->float('amount')->default(0);
            $table->tinyInteger('status')->default(ConsultationPatientStatusConstants::PENDING->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation_doctor_replies');
    }
};
