<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\GeneralSettings;
use Illuminate\Http\Request;

class GeneralSettingsController extends Controller
{
    public function edit(GeneralSettings $settings)
    {
        return view('dashboard.settings.edit', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request, GeneralSettings $settings)
    {
        $request->validate([
            'site_name'              => 'required|string',
            'app_payment_percentage' => 'required|numeric|min:0',
            'urgent_grace_period'    => 'required|numeric|min:0',
            'normal_grace_period'    => 'required|numeric|min:0',
            'tax_percentage'         => 'required|numeric|min:0|max:100',
        ]);

        $settings->site_name              = $request->input('site_name');
        $settings->app_payment_percentage = $request->input('app_payment_percentage');
        $settings->urgent_grace_period    = $request->input('urgent_grace_period');
        $settings->normal_grace_period    = $request->input('normal_grace_period');
        $settings->tax_percentage         = $request->input('tax_percentage');

        $settings->save();

        return redirect()->back()->with('success', __('messages.settings_updated'));
    }
}
