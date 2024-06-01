<?php

namespace App\Services\Repositories;

use App\Models\Consultation;
use App\Models\Doctor;
use App\Repositories\Contracts\DoctorContract;
use App\Repositories\Contracts\NotificationContract;

class ConsultationNotificationService
{
    private NotificationContract $notificationContract;
    private array $notifiedUsers = [];
    private array $notificationData = [];

    public function __construct(NotificationContract $notificationContract)
    {
        $this->notificationContract = $notificationContract;
        $this->notificationData = [
            'title' => 'messages.notification_messages.consultation.%s.title',
            'body' => 'messages.notification_messages.consultation.%s.body',
            'redirect_type' => 'Consultation',
            'redirect_id' => '',
            'users' => $this->notifiedUsers
        ];
    }


    public function newConsultation(Consultation $consultation): void
    {
        if ($consultation->doctor?->user_id) {
            $this->notifiedUsers = [$consultation->doctor->user_id];
        } else {
            $this->notifiedUsers = resolve(DoctorContract::class)->search(['canAcceptUrgentCases' => auth()->id()])
                ->pluck('user_id')->values()->unique()->toArray();
        }
        if (count($this->notifiedUsers) == 0) return;
        $this->notificationData['title'] = __(sprintf($this->notificationData['title'], 'new'));
        $this->notificationData['body'] = __(sprintf($this->notificationData['body'], 'new'));
        $this->notificationData['redirect_id'] = $consultation->id;
        $this->notificationData['users'] = $this->notifiedUsers;
        $this->notificationContract->create($this->notificationData);
    }

    public function vendorReferral(Consultation $consultation): void
    {
        $this->notifiedUsers = $consultation->vendors->pluck('user_id')->toArray();
        if (count($this->notifiedUsers) == 0) return;
        $this->notificationData['title'] = __(sprintf($this->notificationData['title'], 'vendor_referral'));
        $this->notificationData['body'] = __(sprintf($this->notificationData['body'], 'vendor_referral'));
        $this->notificationData['redirect_id'] = $consultation->id;
        $this->notificationData['users'] = $this->notifiedUsers;
        $this->notificationContract->create($this->notificationData);
    }

    public function prescription(Consultation $consultation): void
    {
        $this->patientNotify($consultation, 'prescription');
    }

    public function doctorApprovedMedicalReport(Consultation $consultation): void
    {
        $this->patientNotify($consultation, 'doctor_approved_medical_report');
    }

    public function doctorApprovedUrgentCase(Consultation $consultation): void
    {
        $this->patientNotify($consultation, 'doctor_approved_urgent_case');
    }

    public function doctorCancel(Consultation $consultation): void
    {
        $this->patientNotify($consultation, 'doctor_cancel');
    }

    public function patientCancel(Consultation $consultation): void
    {
        $this->doctorNotify($consultation, 'patient_cancel');
    }

    public function patientAcceptDoctorOffer(Consultation $consultation): void
    {
        $this->doctorNotify($consultation, 'patient_accept_doctor_offer');
    }

    public function patientRejectDoctorOffer(Consultation $consultation): void
    {
        $this->doctorNotify($consultation, 'patient_reject_doctor_offer');
    }

    private function patientNotify($consultation, $message): void
    {
        $this->notifiedUsers = [$consultation->patient->user_id];
        $this->userNotify($consultation, $message);
    }

    private function doctorNotify($consultation, $message): void
    {
        if ($consultation->doctor) {
            $this->notifiedUsers = [$consultation->doctor->user_id];
            $this->userNotify($consultation, $message);
        }
    }

    private function userNotify($consultation, $message): void
    {
        if (count($this->notifiedUsers) == 0) return;
        $this->notificationData['title'] = __(sprintf($this->notificationData['title'], $message));
        $this->notificationData['body'] = __(sprintf($this->notificationData['body'], $message));
        $this->notificationData['redirect_id'] = $consultation->id;
        $this->notificationData['users'] = $this->notifiedUsers;
        $this->notificationContract->create($this->notificationData);
    }

}
