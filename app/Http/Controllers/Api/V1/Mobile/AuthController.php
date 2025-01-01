<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\DoctorRegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\PatientRegisterRequest;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\UserContract;
use App\Services\Repositories\UserAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseApiController
{
    private UserAuthService $userAuthService;
    private array $doctorRelations = [
        'doctor.medicalSpecialities',
        'doctor.academicDegree',
        'doctor.attachments',
        'doctor.city',
        'doctor.scheduleDays.shifts.availableSlots',
        'doctor.hospitals',
        'doctor.universities.academicDegree',
        'doctor.universities.certificate',
        'doctor.universities.university',
        'doctor.universities.medicalSpeciality'
    ];
    private array $patientRelations = ['patient.diseases'];

    public function __construct(UserContract $userContract, UserAuthService $userAuthService)
    {
        parent::__construct($userContract, UserResource::class);
        $this->userAuthService = $userAuthService;
    }

    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'phone' => config('validations.phone.req')
        ]);
        $existedUser = $this->contract->findBy('phone', $request->phone);
        if ($existedUser) {
            $existedUser = $this->userAuthService->sendVerificationCode($existedUser);
            $hasDoctor = $existedUser->doctor()->exists();
            return $this->respondWithSuccess('', [
                'verification_code' => $existedUser->verification_code,
                'has_doctor' => $hasDoctor,
                'is_active' => $existedUser->is_active,
                'is_verified' => $existedUser->is_verified,
            ]);
        } else {
            return $this->respondWithError(__('auth.failed'), 401);
        }
    }

    public function login(LoginRequest $request)
    {
        $loginUser = $this->contract->findByFields(['and' => ['phone' => $request->phone, 'verification_code' => $request->verification_code]]);
        if (env('APP_ENV') == 'local' && $request->verification_code == 1234 && !$loginUser) {
            // $loginUser = $this->contract->findByFields(['and' => ['phone' => $request->phone, 'is_active' => true]]); // for testing remove this line in production
            $loginUser = \App\Models\User::where('phone', $request->phone)->first();
        }
        if ($loginUser && $loginUser->is_active) {
            Auth::login($loginUser);
            $this->userAuthService->verifyUser($loginUser);
            $loginUser->api_token = $this->userAuthService->createToken($loginUser, $request->validated());
            $loginUser->load('patient', 'doctor');
            return $this->respondWithModel($loginUser);
        } else {
            if ($loginUser && !$loginUser->is_active)
                return $this->respondWithError(__('auth.not_active'), 422);
            return $this->respondWithError(__('auth.failed'), 422);
        }
    }

    public function registerUserAsPatient(PatientRegisterRequest $request)
    {
        $patient = $this->userAuthService->registerUserAsPatient($request->validated());
        $user = $patient->user;
        $user->load('patient');
        return $this->respondWithModel($user);
    }

    public function registerUserAsDoctor(DoctorRegisterRequest $request)
    {
        $doctor = $this->userAuthService->registerUserAsDoctor($request->validated());
        $user = $doctor->user;
        $user->load($this->doctorRelations);
        return $this->respondWithModel($user);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();
        $this->userAuthService->setUserAsNotVerified($user);
        return $this->respondWithSuccess();
    }

    public function profile()
    {
        $relations = array_merge($this->doctorRelations, $this->patientRelations);
        return $this->respondWithModel(auth()->user()->load($relations));
    }
}
