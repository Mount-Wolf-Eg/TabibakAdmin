<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

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
    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $user = User::find(auth()->id());
            $old_password = auth()->user()->password;

            if (Hash::check($request->old_password, $old_password)) {
                if (Hash::check($request->new_password, $old_password)) {
                    return back()->with('error', __('messages.actions_messages.old_new_passwords_match'));
                }
                $user->password = bcrypt($request->password);
                $user->save();
                return redirect()->route('profile')->with('success', __('messages.actions_messages.update_success'));
            }
            return back()->with('error', __('messages.actions_messages.old_pass_not_correct'));
        } catch (Exception $e) {
            return $this->redirectBack()->with('error', $e->getMessage());
        }
    }
}
