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

class SendConsultationCall implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $consultation_id;

    /**
     * Create a new job instance.
     */
    public function __construct($consultation_id)
    {
        $this->consultation_id = $consultation_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $consultationNotificationService = resolve(ConsultationNotificationService::class);

        $consultation = Consultation::whereHas('doctor')
            ->whereHas('patient')
            ->whereHas('doctorScheduleDayShift', function ($query) {
                $query->whereHas('day', function ($query) {
                    $query->whereDate('date', now()->toDateString());
                })->whereTime('from_time', now()->toTimeString());
            })
            ->whereIn('status', [ConsultationStatusConstants::PENDING, ConsultationStatusConstants::URGENT_PATIENT_APPROVE_DOCTOR_OFFER])
            ->find($this->consultation_id);

        if ($consultation) {
            $consultationNotificationService->reminderPatient($consultation);
            $consultationNotificationService->reminderPatient($consultation);
        }
    }
}

