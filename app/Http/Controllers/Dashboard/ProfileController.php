<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use App\Models\Vendor;
use App\Repositories\Contracts\VendorServiceContract;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    private VendorServiceContract $vendorServiceContract;

    public function __construct(VendorServiceContract $vendorServiceContract)
    {
        $this->vendorServiceContract = $vendorServiceContract;
    }

    public function profile()
    {
        $user = auth()->user();
        if ($user->vendor) $user->vendor->load('vendorServices');
        $services = $this->vendorServiceContract->search(['active' => true], [], ['limit' => 0, 'page' => 0]);
        return view('dashboard.profile.index', compact(['user', 'services']));
    }

    public function updateProfile(ProfileRequest $request)
    {
        try {
            auth()->user()->update($request->validated('user'));
            if (isset($request['vendor_profile'])) {
                auth()->user()->vendor()->update($request->only('address'));
                $vendorId = Vendor::where('user_id', auth()->id())->first()->id;
                DB::table('service_vendor')->where('vendor_id', $vendorId)->delete();
                foreach ($request['services'] as $service) {
                    DB::table('service_vendor')->insert([
                        'vendor_service_id' => $service,
                        'vendor_id' => $vendorId
                    ]);
                }
            }
            return redirect()->route('profile')->with('success', __('messages.actions_messages.update_success'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
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
            return back()->with('error', $e->getMessage());
        }
    }
}
