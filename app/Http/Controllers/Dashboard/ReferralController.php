<?php

namespace App\Http\Controllers\Dashboard;

use App\Constants\ConsultationVendorStatusConstants;
use App\Http\Controllers\BaseWebController;
use App\Models\Consultation;
use App\Repositories\Contracts\ConsultationContract;
use App\Repositories\Contracts\DoctorContract;
use App\Services\Repositories\ConsultationVendorService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReferralController extends BaseWebController
{
    private ConsultationVendorService $consultationVendorService;

    /**
     * PatientConsultationController constructor.
     * @param ConsultationContract $contract
     * @param ConsultationVendorService $consultationVendorService
     */
    public function __construct(ConsultationContract $contract, ConsultationVendorService $consultationVendorService)
    {
        parent::__construct($contract, 'dashboard', 'referral');
        $this->consultationVendorService = $consultationVendorService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View|Factory|Application
    {
        $filters = $request->all();
        $filters['type'] = 3;
        
        if (!auth()->user()->can('view-all-referral'))
            $filters['mineAsVendor'] = true;
        $doctors = resolve(DoctorContract::class)->search([], ['user'], ['limit' => 0]);
        $vendorStatuses = collect(ConsultationVendorStatusConstants::valuesCollection());
        $types = collect(Consultation::types());
        $resources = $this->contract->search($filters, ['doctor', 'patient', 'medicalSpeciality', 'vendors']);
        return $this->indexBlade([
            'resources' => $resources,
            'doctors' => $doctors,
            'vendorStatuses' => $vendorStatuses,
            'types' => $types
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Consultation $consultation
     *
     * @return View|Factory|Application
     */
    public function show($id): View|Factory|Application
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->load('attachments.user', 'notes.user', 'vendors');
        return $this->showBlade(['consultation' => $consultation]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Consultation $consultation
     *
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $consultation = Consultation::findOrFail($id);
        $this->contract->remove($consultation);
        return $this->redirectBack()->with('success', __('messages.actions_messages.delete_success'));
    }

    public function vendorAccept($id)
    {
        $consultation = Consultation::findOrFail($id);
        $updateStatus = $this->consultationVendorService->accept($consultation, auth()->user()->vendor);
        if (!$updateStatus)
            return $this->redirectBack()->with('error', __('messages.not_allowed'));
        return $this->redirectBack()->with('success', __('messages.actions_messages.update_success'));
    }

    public function vendorReject($id)
    {
        $consultation = Consultation::findOrFail($id);
        $updateStatus = $this->consultationVendorService->reject($consultation, auth()->user()->vendor);
        if (!$updateStatus)
            return $this->redirectBack()->with('error', __('messages.not_allowed'));
        return $this->redirectBack()->with('success', __('messages.actions_messages.update_success'));
    }
}
