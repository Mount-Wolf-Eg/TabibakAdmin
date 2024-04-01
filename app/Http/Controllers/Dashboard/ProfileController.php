<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = auth()->user();
        return view('dashboard.profile.index', compact(['user']));
    }
    public function changePassword()
    {
        return view('dashboard.profile.change-password');
    }
}
