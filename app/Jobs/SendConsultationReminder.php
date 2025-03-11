<?php

namespace App\Jobs;

use App\Constants\ConsultationStatusConstants;
use App\Models\Consultation;
use App\Services\Repositories\ConsultationNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class SendConsultationReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $consultationNotificationService = resolve(ConsultationNotificationService::class);

        // Fetch consultations that require reminders
        $consultations = Consultation::whereHas('doctor')
            ->whereHas('patient')
            ->whereHas('doctorScheduleDayShift', function ($query) {
                $query->whereDate('date', now()->toDateString()) // Check today's shifts
                      ->whereTime('from_time', '>', now()->toTimeString()); // Only upcoming
            })
            ->whereIn('status', [ConsultationStatusConstants::PENDING, ConsultationStatusConstants::URGENT_PATIENT_APPROVE_DOCTOR_OFFER])
            ->where(function ($query) {
                $query->where('patient_reminded', false)
                      ->orWhere('doctor_reminded', false);
            })
            ->get();

        foreach ($consultations as $consultation) {
            $shiftStartTime = Carbon::parse($consultation->doctorScheduleDayShift->from_time);

            // Handle doctor reminder
            if (!$consultation->doctor_reminded) {
                $reminderTime = $shiftStartTime->copy()->subMinutes($consultation->doctor->reminder_before_consultation);
                if (now()->greaterThanOrEqualTo($reminderTime)) {
                    $consultationNotificationService->doctorReminder($consultation);
                }
            }

            // Handle patient reminder
            if (!$consultation->patient_reminded) {
                $reminderTime = Carbon::parse($consultation->reminder_at);
                if (now()->greaterThanOrEqualTo($reminderTime)) {
                    $consultationNotificationService->patientReminder($consultation);
                }
            }
        }
    }
}
