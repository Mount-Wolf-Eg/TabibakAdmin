<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\GeneralSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use App\Http\Requests\FeaturedListRequest;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\DB;

class FeaturedListController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return View|Factory|Application
     */
    public function edit(): View|Factory|Application
    {
        $featuredListTitle = GeneralSettings::getSettingValue('featured_list_title');
        $featuredListText = GeneralSettings::getSettingValue('featured_list_text');
        return view('dashboard.featured-list.edit', compact(['featuredListTitle', 'featuredListText']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FeaturedListRequest $request
     *
     * @return RedirectResponse
     */
    public function update(GeneralSettings $settings, FeaturedListRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $settings->featured_list_title = $validated['featured_list_title'];
        $settings->featured_list_text = $validated['featured_list_text'];
        $settings->save();
        return redirect()->back()->with('success', __('messages.actions_messages.update_success'));
    }
}
