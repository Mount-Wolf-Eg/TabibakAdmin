<?php

namespace App\Http\Controllers\Dashboard;

use App\Constants\VendorTypeConstants;
use App\Http\Controllers\Controller;
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
        if(auth()->user()->vendor){
            return $this->vendorOverview();
        }else{
            return $this->adminOverview();
        }
    }

    public function vendorOverview()
    {
        $totalConsultations = $this->consultationContract->freshRepo()
            ->countWithFilters(['mineAsVendor' => true]);
        $totalApprovedConsultations = $this->consultationContract->freshRepo()
            ->countWithFilters(['mineAsVendor' => true, 'vendorAcceptedStatus'=> true]);
        $totalRejectedConsultations = $this->consultationContract->freshRepo()
            ->countWithFilters(['mineAsVendor' => true, 'vendorRejectedStatus'=> true]);
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

        $totalAppointments=0;
        $totalPendingBookings=0;
        $totalCompletedBookings=0;
        $totalCanceledBookings=0;
        $totalRescheduled=0;
        $AverageDurationOfConsultation=0;
        $totalVideoConsultationsCompleted=0;
        $totalAudioConsultationComplete=0;
        $totalChatConsultationComplete=0;
        $totalNewPatients=0;

        $averageRatingPerDoctor=0;
        $averageNumberOfConsultationsPerDoctor=0;
        $averageConsultationDurationPerDoctor=0;


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
        ]));
    }

    public function download(Request $request): BinaryFileResponse
    {
        $fileName = 'storage/uploads/' . $request['dir'] . '/' . $request['file_name'];
        $file = public_path($fileName);
        return response()->download($file, $request['file_name'], ['Content-Type' => 'text/plain']);
    }

    private function getVendorCount ($typename)
    {
        return Vendor::query()->whereHas('vendorType', function ($query) use ($typename) {
            $query->where('name->en', $typename);
        })->count();
    }
}
