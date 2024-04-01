<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseWebController;
use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Contracts\RoleContract;
use App\Repositories\Contracts\UserContract;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends BaseWebController
{
    protected RoleContract $roleContract;

    /**
     * UserController constructor.
     * @param UserContract $contract
     */
    public function __construct(UserContract $contract, RoleContract $roleContract)
    {
        parent::__construct($contract, 'dashboard');
        $this->roleContract = $roleContract;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View|Factory|Application
    {
        $resources = $this->contract->search($request->all(), ['roles']);
        return $this->indexBlade(['resources' => $resources]);
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        $roles = $this->roleContract->search(['active' => true], [], ['limit' => 0, 'page' => 0]);
        return $this->createBlade(['roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     *
     * @return RedirectResponse
     */
    public function store(UserRequest $request): RedirectResponse
    {
        $this->contract->create($request->validated());
        return $this->redirectToIndex()->with('success', __('messages.actions_messages.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     *
     * @return View|Factory|Application
     */
    public function show(User $user): View|Factory|Application
    {
        return $this->showBlade(['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     *
     * @return View|Factory|Application
     */
    public function edit(User $user): View|Factory|Application
    {
        $roles = $this->roleContract->search(['active' => true], [], ['limit' => 0, 'page' => 0]);
        return $this->editBlade(['user' => $user, 'roles' => $roles]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param User $user
     *
     * @return RedirectResponse
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $this->contract->update($user, $request->validated());
        if (request()->has('profile')) {
            return redirect()->route('profile')->with('success', __('messages.actions_messages.update_success'));
        }
        return $this->redirectToIndex()->with('success', __('messages.actions_messages.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     *
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
       $this->contract->remove($user);
       return $this->redirectBack()->with('success', __('messages.actions_messages.delete_success'));
    }

    /**
     * active & inactive the specified resource from storage.
     * @param User $user
     * @return RedirectResponse
     */
    public function changeActivation(User $user): RedirectResponse
    {
        $this->contract->toggleField($user, 'is_active');
        return $this->redirectBack()->with('success', __('messages.actions_messages.update_success'));
    }
}
