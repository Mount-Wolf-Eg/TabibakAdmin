<?php

namespace App\Http\Controllers\Dashboard;

use App\Constants\DoctorRequestStatusConstants;
use App\Http\Requests\DoctorRequest;
use App\Models\Doctor;
use App\Repositories\Contracts\AcademicDegreeContract;
use App\Repositories\Contracts\DoctorContract;
use App\Repositories\Contracts\MedicalSpecialityContract;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseWebController;
use App\Repositories\Contracts\UserContract;
use App\Services\Repositories\UserNotificationService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DoctorController extends BaseWebController
{
    protected MedicalSpecialityContract $medicalSpecialityContract;
    protected AcademicDegreeContract $academicDegreeContract;
    protected UserNotificationService $userNotificationService;

    /**
     * DoctorController constructor.
     * @param DoctorContract $contract
     * @param MedicalSpecialityContract $medicalSpecialityContract
     * @param AcademicDegreeContract $academicDegreeContract
     */
    public function __construct(DoctorContract $contract, MedicalSpecialityContract $medicalSpecialityContract, 
    AcademicDegreeContract $academicDegreeContract, UserNotificationService $userNotificationService)
    {
        parent::__construct($contract, 'dashboard');
        $this->medicalSpecialityContract = $medicalSpecialityContract;
        $this->academicDegreeContract = $academicDegreeContract;
        $this->userNotificationService = $userNotificationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View|Factory|Application
    {
        $resources = $this->contract->search($request->all(), ['medicalSpecialities']);
        return $this->indexBlade(['resources' => $resources]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        $specialities = $this->medicalSpecialityContract->search(['active' => true], [], ['limit' => 0, 'page' => 0]);
        $academicDegrees = $this->academicDegreeContract->search(['active' => true], [], ['limit' => 0, 'page' => 0]);
        return $this->createBlade(['specialities' => $specialities, 'academicDegrees' => $academicDegrees]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DoctorRequest $request
     *
     * @return RedirectResponse
     */
    public function store(DoctorRequest $request): RedirectResponse
    {
        $this->contract->create($request->validated());
        return $this->redirectToIndex()->with('success', __('messages.actions_messages.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param Doctor $doctor
     *
     * @return View|Factory|Application
     */
    public function show(Doctor $doctor): View|Factory|Application
    {
        return $this->showBlade(['doctor' => $doctor]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Doctor $doctor
     *
     * @return View|Factory|Application
     */
    public function edit(Doctor $doctor): View|Factory|Application
    {
        $specialities = $this->medicalSpecialityContract->search(['active' => true], [], ['limit' => 0, 'page' => 0]);
        $academicDegrees = $this->academicDegreeContract->search(['active' => true], [], ['limit' => 0, 'page' => 0]);
        return $this->editBlade(['doctor' => $doctor, 'specialities' => $specialities, 'academicDegrees' => $academicDegrees]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DoctorRequest $request
     * @param Doctor $doctor
     *
     * @return RedirectResponse
     */
    public function update(DoctorRequest $request, Doctor $doctor): RedirectResponse
    {
        $this->contract->update($doctor, $request->validated());
        return $this->redirectToIndex()->with('success', __('messages.actions_messages.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Doctor $doctor
     *
     * @return RedirectResponse
     */
    public function destroy(Doctor $doctor): RedirectResponse
    {
        resolve(UserContract::class)->remove($doctor->user);
        $this->contract->remove($doctor);
        return $this->redirectBack()->with('success', __('messages.actions_messages.delete_success'));
    }

    /**
     * active & inactive the specified resource from storage.
     * @param Doctor $doctor
     * @return RedirectResponse
     */
    public function changeActivation(Doctor $doctor): RedirectResponse
    {
        $this->contract->toggleField($doctor, 'is_active');
        return $this->redirectBack()->with('success', __('messages.actions_messages.update_success'));
    }

    /**
     * approve the specified resource from storage.
     * @param Doctor $doctor
     * @return RedirectResponse
     */
    public function approve(Doctor $doctor): RedirectResponse
    {
        if ($doctor->request_status?->is(DoctorRequestStatusConstants::PENDING)) {
            $this->contract->update($doctor, ['request_status' => DoctorRequestStatusConstants::APPROVED->value]);
            $this->userNotificationService->approveDoctor($doctor->user);
        } else {
            return $this->redirectBack()->with('error', __('messages.errors.doctor_request_pending'));
        }
        return $this->redirectBack()->with('success', __('messages.actions_messages.update_success'));
    }

    /**
     * reject the specified resource from storage.
     * @param Doctor $doctor
     * @return RedirectResponse
     */
    public function reject(Doctor $doctor): RedirectResponse
    {
        if ($doctor->request_status?->is(DoctorRequestStatusConstants::PENDING)) {
            $this->contract->update($doctor, ['request_status' => DoctorRequestStatusConstants::REJECTED->value]);
        } else {
            return $this->redirectBack()->with('error', __('messages.errors.doctor_request_pending'));
        }
        return $this->redirectBack()->with('success', __('messages.actions_messages.update_success'));
    }
}
