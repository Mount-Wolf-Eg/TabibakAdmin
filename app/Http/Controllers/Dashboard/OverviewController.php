<?php

namespace App\Http\Controllers\Dashboard;

use App\Constants\ConsultationContactTypeConstants;
use App\Constants\ConsultationStatusConstants;
use App\Constants\ConsultationTypeConstants;
use App\Constants\VendorTypeConstants;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\MedicalSpeciality;
use App\Models\Rate;
use App\Models\User;
use App\Models\Vendor;
use App\Repositories\Contracts\ConsultationContract;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OverviewController extends Controller
{
    private ConsultationContract $consultationContract;

    public function __construct(ConsultationContract $consultationContract)
    {
        $this->consultationContract = $consultationContract;
    }

    public function __invoke()
    {
        if (auth()->user()->vendor) {
            return $this->vendorOverview();
        } else {
            return $this->adminOverview();
        }
    }

    public function vendorOverview()
    {
        $totalConsultations = $this->consultationContract->freshRepo()
            ->countWithFilters(['mineAsVendor' => true]);
        $totalApprovedConsultations = $this->consultationContract->freshRepo()
            ->countWithFilters(['mineAsVendor' => true, 'vendorAcceptedStatus' => true]);
        $totalRejectedConsultations = $this->consultationContract->freshRepo()
            ->countWithFilters(['mineAsVendor' => true, 'vendorRejectedStatus' => true]);
        return view('dashboard.home.vendor-overview', compact([
            'totalConsultations',
            'totalApprovedConsultations',
            'totalRejectedConsultations',
        ]));
    }

    public function adminOverview()
    {
        $patientsCount = User::query()->whereHas('patient')->count();
        $doctorsCount = User::query()->whereHas('doctor')->count();
        $vendorsCount = Vendor::query()->count();
        $hospitalsCount = $this->getVendorCount(VendorTypeConstants::HOSPITAL->value);
        $clinicsCount = $this->getVendorCount(VendorTypeConstants::CLINIC->value);
        $pharmaciesCount = $this->getVendorCount(VendorTypeConstants::PHARMACY->value);
        $homeCaresCount = $this->getVendorCount(VendorTypeConstants::HOMECARE->value);
        $labsCount = $this->getVendorCount(VendorTypeConstants::LAB->value);
        $totalTransactions = 0;
        $totalRevenues = 0;

        $totalAppointments = Consultation::where('type', ConsultationTypeConstants::WITH_APPOINTMENT)->count();
        $totalPendingBookings = Consultation::where('status', ConsultationStatusConstants::PENDING)->count();
        $totalCompletedBookings = Consultation::where('status', ConsultationStatusConstants::DOCTOR_APPROVED_MEDICAL_REPORT)->count();
        $totalCanceledBookings = Consultation::whereIn('status', [ConsultationStatusConstants::PATIENT_CANCELLED, ConsultationStatusConstants::DOCTOR_CANCELLED])->count();
        $totalRescheduled = Consultation::whereIn('status', [ConsultationStatusConstants::NEEDS_RESCHEDULE])->count();

        $AverageDurationOfConsultation = 3;

        $totalVideoConsultationsCompleted = Consultation::where('status', ConsultationStatusConstants::DOCTOR_APPROVED_MEDICAL_REPORT)->where('contact_type', ConsultationContactTypeConstants::VIDEO)->count();
        $totalAudioConsultationComplete = Consultation::whereIn('status', [ConsultationStatusConstants::DOCTOR_APPROVED_MEDICAL_REPORT])->where('contact_type', ConsultationContactTypeConstants::AUDIO)->count();
        $totalChatConsultationComplete = Consultation::whereIn('status', [ConsultationStatusConstants::DOCTOR_APPROVED_MEDICAL_REPORT])->where('contact_type', ConsultationContactTypeConstants::CHAT)->count();

        $totalNewPatients = User::query()->whereHas('patient')->count();

        $averageRatingPerDoctor = Rate::where('rateable_type', 'Doctor')->avg('value');
        $averageNumberOfConsultationsPerDoctor = Consultation::groupBy('doctor_id')->selectRaw('doctor_id, COUNT(*) as consultation_count')->get()->avg('consultation_count');

        $averageConsultationDurationPerDoctor = 3;

        $topThreeDoctors = Doctor::with(['medicalSpecialities'])
            ->withAvg('rates', 'value')
            ->withCount('consultations')
            ->orderBy('rates_avg_value', 'desc')
            ->take(3)
            ->get();

        $topMedicalSpecialty = MedicalSpeciality::withCount('consultations')
            ->orderBy('consultations_count', 'desc')
            ->having('consultations_count', '>', 0)
            ->take(10) // Limit to top 10 for better visualization
            ->get()->sortBy('consultations_count');

        $mostBookedDoctors = Doctor::withCount('consultations')
            ->orderBy('consultations_count', 'desc')
            ->having('consultations_count', '>', 0)
            ->take(10) // Limit to top 10 for better visualization
            ->get()->sortBy('consultations_count');

        $topConsultationLocation = City::withCount(['users as consultations_count' => function ($query) {
            $query->whereHas('doctor', function ($q) {
                $q->withCount('consultations');
            });
        }])
            ->having('consultations_count', '>', 0)
            ->orderByDesc('consultations_count')
            ->take(10)
            ->get();

        return view('dashboard.home.admin-overview', compact([
            'patientsCount',
            'doctorsCount',
            'vendorsCount',
            'hospitalsCount',
            'clinicsCount',
            'pharmaciesCount',
            'homeCaresCount',
            'labsCount',
            'totalTransactions',
            'totalRevenues',
            'totalAppointments',
            'totalPendingBookings',
            'totalCompletedBookings',
            'totalCanceledBookings',
            'totalRescheduled',
            'AverageDurationOfConsultation',
            'totalVideoConsultationsCompleted',
            'totalAudioConsultationComplete',
            'totalChatConsultationComplete',
            'totalNewPatients',
            'averageRatingPerDoctor',
            'averageNumberOfConsultationsPerDoctor',
            'averageConsultationDurationPerDoctor',
            'topThreeDoctors',
            'topMedicalSpecialty',
            'mostBookedDoctors',
            'topConsultationLocation'
        ]));
    }

    public function download(Request $request): BinaryFileResponse
    {
        $fileName = 'storage/uploads/' . $request['dir'] . '/' . $request['file_name'];
        $file = public_path($fileName);
        return response()->download($file, $request['file_name'], ['Content-Type' => 'text/plain']);
    }

    private function getVendorCount($typename)
    {
        return Vendor::query()->whereHas('vendorType', function ($query) use ($typename) {
            $query->where('name->en', $typename);
        })->count();
    }
}
