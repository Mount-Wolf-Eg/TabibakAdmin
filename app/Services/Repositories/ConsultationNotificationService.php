<?php

namespace App\Services\Repositories;

use App\Constants\NotificationTypeConstants;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Repositories\Contracts\DoctorContract;
use App\Repositories\Contracts\NotificationContract;
use Carbon\Carbon;

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
            'type' => '',
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
            $this->notifiedUsers = resolve(DoctorContract::class)->search(['canAcceptUrgentCases' => auth()->id(), 'medicalSpeciality' => $consultation->medical_speciality_id])
                ->pluck('user_id')->values()->unique()->toArray();
        }
        $this->doctorNotify($consultation, 'new');
    }

    public function vendorReferral(Consultation $consultation): void
    {
        $this->notifiedUsers = $consultation->vendors->pluck('user_id')->toArray();
        if (count($this->notifiedUsers) == 0) return;
        $this->notificationData['title'] = __(sprintf($this->notificationData['title'], 'vendor_referral'));
        $this->notificationData['body'] = __(sprintf($this->notificationData['body'], 'vendor_referral'));
        $this->notificationData['type'] = NotificationTypeConstants::VENDOR->value;
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

    public function doctorReschedule(Consultation $consultation): void
    {
        $this->patientNotify($consultation, 'doctor_need_reschedule', ['action' => 'reschedule']);
    }

    public function patientCancel(Consultation $consultation): void
    {
        $this->notifiedUsers = [$consultation->doctor->user_id];
        $this->doctorNotify($consultation, 'patient_cancel');
    }

    public function patientAcceptDoctorOffer(Consultation $consultation): void
    {
        $this->notifiedUsers = [$consultation->doctor->user_id];
        $this->doctorNotify($consultation, 'patient_accept_doctor_offer', []);
    }

    public function patientRejectDoctorOffer(Consultation $consultation, Doctor $doctor): void
    {
        $this->notifiedUsers = [$doctor->user_id];
        $this->doctorNotify($consultation, 'patient_reject_doctor_offer');
    }

    public function doctorReferral(Consultation $consultation): void
    {
        $this->doctorNotify($consultation, 'doctor_referral');
    }

    public function reminderDoctor(Consultation $consultation): void
    {
        $shiftStartTime = Carbon::parse($consultation->doctorScheduleDayShift->from_time);
        $reminderTime   = $shiftStartTime->copy()->subMinutes($consultation->doctor->reminder_before_consultation);

        // Ensure we only send the reminder once and it's the right time
        if ($consultation->doctor_reminded || now()->lessThan($reminderTime)) {
            return;
        }

        // Mark as reminded
        $consultation->doctor_reminded = true;
        $consultation->save();

        // Notify doctor
        $this->notifiedUsers = [$consultation->doctor->user->id];

        // Define custom title & body for doctor reminder
        $title = __("messages.notification_messages.consultation.doctor_reminder.title");
        $body  = __("messages.notification_messages.consultation.doctor_reminder.body", [
            'doctor_name'       => $consultation->doctor->user->name,
            'consultation_time' => $shiftStartTime->format('h:i A')
        ]);

        $this->doctorNotify($consultation, 'reminder', [], $title, $body);
    }

    public function reminderPatient(Consultation $consultation): void
    {
        if (!$consultation->reminder_at) {
            return; // Skip if reminder_at is not set
        }

        $reminderTime = Carbon::parse($consultation->reminder_at);

        // Ensure we only send the reminder once and it's the right time
        if ($consultation->patient_reminded || now()->lessThan($reminderTime)) {
            return;
        }

        // Mark as reminded
        $consultation->patient_reminded = true;
        $consultation->save();

        // Notify patient
        $this->notifiedUsers = [$consultation->patient->user->id];

        // Define custom title & body for patient reminder
        $title = __("messages.notification_messages.consultation.patient_reminder.title");
        $body  = __("messages.notification_messages.consultation.patient_reminder.body", [
            'patient_name'      => $consultation->patient->user->name,
            'doctor_name'       => $consultation->doctor->user->name,
            'consultation_time' => Carbon::parse($consultation->doctorScheduleDayShift->from_time)->format('h:i A')
        ]);

        $this->patientNotify($consultation, 'reminder', [], $title, $body);
    }

    public function doctorCall(Consultation $consultation): void
    {
        $this->notifiedUsers = [$consultation->doctor->user_id];
        $this->doctorNotify($consultation, 'call', ["doctorId" => (string) $consultation->doctor->id, 
        "doctorName" => $consultation->doctor->user->name,
        "patientName" => $consultation->patient->user->name,
        "patientId" => (string) $consultation->patient->id,
        "contactType" => (string) $consultation->contact_type->value,
        "nationalId" => (string) $consultation->doctor->national_id,
        "createdAt" => (string) $consultation->created_at, "type" => "call", "callId" => str_pad(mt_rand(0, 9999999), 7, '0', STR_PAD_LEFT), "callerName" => "sasaa"]);
    }

    public function patientCall(Consultation $consultation): void
    {
        $this->notifiedUsers = [$consultation->patient->user_id];
        $this->patientNotify($consultation, 'call', ["doctorId" => (string) $consultation->doctor->id, 
        "doctorName" => $consultation->doctor->user->name,
        "patientName" => $consultation->patient->user->name,
        "patientId" => (string) $consultation->patient->id,
        "contactType" => (string) $consultation->contact_type->value,
        "nationalId" => (string) $consultation->doctor->national_id,
        "createdAt" => (string) $consultation->created_at, "type" => "call", "callId" => str_pad(mt_rand(0, 9999999), 7, '0', STR_PAD_LEFT), "callerName" => "sasaa"]);
    }

    private function patientNotify($consultation, $message, $data = [], $title = null, $body = null): void
    {
        $this->notifiedUsers = [$consultation->patient->user_id];
        $this->notificationData['type'] = NotificationTypeConstants::PATIENT->value;
        $this->notificationData['data'] = $data;
        $this->userNotify($consultation, $message, $data, $title, $body);
    }

    private function doctorNotify($consultation, $message, $data = [], $title = null, $body = null): void
    {
        $this->notificationData['type'] = NotificationTypeConstants::DOCTOR->value;
        $this->userNotify($consultation, $message, $data, $title, $body);
    }

    private function userNotify($consultation, $message, $data = [], $title = null, $body = null): void
    {
        if (count($this->notifiedUsers) == 0) return;

        $this->notificationData['title']       = $title ?? __(sprintf($this->notificationData['title'], $message));
        $this->notificationData['body']        = $body ?? __(sprintf($this->notificationData['body'], $message));
        $this->notificationData['redirect_id'] = $consultation->id;
        $this->notificationData['users']       = $this->notifiedUsers;
        $this->notificationData['data']        = $data;

        $this->notificationContract->create($this->notificationData);
    }
}
