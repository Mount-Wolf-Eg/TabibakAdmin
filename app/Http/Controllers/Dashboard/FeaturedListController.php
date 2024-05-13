<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\FeaturedListRequest;
use App\Models\FeaturedList;
use App\Repositories\Contracts\FeaturedListContract;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseWebController;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class FeaturedListController extends BaseWebController
{
    /**
     * FeaturedListController constructor.
     * @param FeaturedListContract $contract
     */
    public function __construct(FeaturedListContract $contract)
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
     * @param FeaturedListRequest $request
     *
     * @return RedirectResponse
     */
    public function store(FeaturedListRequest $request): RedirectResponse
    {
        $this->contract->create($request->validated());
        return $this->redirectToIndex()->with('success', __('messages.actions_messages.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param FeaturedList $featuredList
     *
     * @return View|Factory|Application
     */
    public function show(FeaturedList $featuredList): View|Factory|Application
    {
        return $this->showBlade(['featuredList' => $featuredList]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param FeaturedList $featuredList
     *
     * @return View|Factory|Application
     */
    public function edit(FeaturedList $featuredList): View|Factory|Application
    {
        return $this->editBlade(['featuredList' => $featuredList]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FeaturedListRequest $request
     * @param FeaturedList $featuredList
     *
     * @return RedirectResponse
     */
    public function update(FeaturedListRequest $request, FeaturedList $featuredList): RedirectResponse
    {
        $this->contract->update($featuredList, $request->validated());
        return $this->redirectToIndex()->with('success', __('messages.actions_messages.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param FeaturedList $featuredList
     *
     * @return RedirectResponse
     */
    public function destroy(FeaturedList $featuredList): RedirectResponse
    {
       $this->contract->remove($featuredList);
       return $this->redirectBack()->with('success', __('messages.actions_messages.delete_success'));
    }

    /**
     * active & inactive the specified resource from storage.
     * @param FeaturedList $featuredList
     * @return RedirectResponse
     */
    public function changeActivation(FeaturedList $featuredList): RedirectResponse
    {
        $this->contract->toggleField($featuredList, 'is_active');
        return $this->redirectBack()->with('success', __('messages.actions_messages.update_success'));
    }
}
