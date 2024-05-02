<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\PatientRequest;
use App\Models\Patient;
use App\Repositories\Contracts\PatientContract;
use App\Repositories\Contracts\UserContract;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseWebController;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PatientController extends BaseWebController
{
    /**
     * PatientController constructor.
     * @param PatientContract $contract
     */
    public function __construct(PatientContract $contract)
    {
        parent::__construct($contract, 'dashboard');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View|Factory|Application
    {
        $resources = $this->contract->search($request->all());
        return $this->indexBlade(['resources' => $resources]);
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return $this->createBlade();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PatientRequest $request
     *
     * @return RedirectResponse
     */
    public function store(PatientRequest $request): RedirectResponse
    {
        $this->contract->create($request->validated());
        return $this->redirectToIndex()->with('success', __('messages.actions_messages.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param Patient $patient
     *
     * @return View|Factory|Application
     */
    public function show(Patient $patient): View|Factory|Application
    {
        return $this->showBlade(['patient' => $patient]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Patient $patient
     *
     * @return View|Factory|Application
     */
    public function edit(Patient $patient): View|Factory|Application
    {
        return $this->editBlade(['patient' => $patient]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PatientRequest $request
     * @param Patient $patient
     *
     * @return RedirectResponse
     */
    public function update(PatientRequest $request, Patient $patient): RedirectResponse
    {
        $this->contract->update($patient, $request->validated());
        return $this->redirectToIndex()->with('success', __('messages.actions_messages.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Patient $patient
     *
     * @return RedirectResponse
     */
    public function destroy(Patient $patient): RedirectResponse
    {
        try {
            $this->contract->remove($patient);
            resolve(UserContract::class)->remove($patient->user);
            return $this->redirectBack()->with('success', __('messages.actions_messages.delete_success'));
        }catch (Exception $e){
            return $this->redirectBack()->with('error', $e->getMessage());
        }
    }

    /**
     * active & inactive the specified resource from storage.
     * @param Patient $patient
     * @return RedirectResponse
     */
    public function changeActivation(Patient $patient): RedirectResponse
    {
        $this->contract->toggleField($patient, 'is_active');
        resolve(UserContract::class)->toggleField($patient->user, 'is_active');
        return $this->redirectBack()->with('success', __('messages.actions_messages.update_success'));
    }
}
