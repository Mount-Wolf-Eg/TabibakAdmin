<?php

namespace App\Services\Repositories;

use App\Jobs\SendVerificationCodeToUser;
use App\Notifications\VerificationCodeNotification;
use App\Repositories\Contracts\DoctorContract;
use App\Repositories\Contracts\PatientContract;
use App\Repositories\Contracts\UserContract;
use Carbon\Carbon;

class UserAuthService
{
    private UserContract $contract;
    private PatientContract $patientContract;
    private DoctorContract $doctorContract;
    public function __construct(UserContract $contract, PatientContract $patientContract, DoctorContract $doctorContract)
    {
        $this->contract = $contract;
        $this->patientContract = $patientContract;
        $this->doctorContract = $doctorContract;
    }

    public function sendVerificationCode($user, $to = 'phone')
    {
        // $code = rand(1000, 9999);
        $code = 1234; // for testing
        $user = $this->contract->update($user, ['verification_code' => $code]);
        // SendVerificationCodeToUser::dispatch($user, $to);
        $user?->notify(new VerificationCodeNotification($code));
        return $user;
    }

    public function verifyUser($user, $type = 'phone')
    {
        if ($type == 'phone') {
            return $this->contract->update($user, ['phone_verified_at' => Carbon::now()]);
        } else {
            return $this->contract->update($user, ['email_verified_at' => Carbon::now()]);
        }
    }

    public function setUserAsNotVerified($user)
    {
        return $this->contract->update($user, ['verification_code' => null, 'phone_verified_at' => null]);
    }

    public function createToken($model, $data): mixed
    {
        $apiToken = $model->createToken($data['device_name'] ?? request()?->header('User-Agent'));
        $accessToken = $apiToken->accessToken;
        $accessToken->device_id = $data['device_id'] ?? null;
        $accessToken->device_os = $data['device_name'] ?? null;
        $accessToken->device_os_version = $data['device_os_version'] ?? null;
        $accessToken->app_version = $data['app_version'] ?? null;
        $accessToken->timezone = $data['timezone'] ?? null;
        $accessToken->fcm_token = $data['fcm_token'] ?? null;
        $apiToken->accessToken->save();
        return $apiToken->plainTextToken;
    }

    public function registerUserAsPatient($data)
    {
        $patient = $this->patientContract->create($data);
        $this->sendVerificationCode($patient->user);
        return $patient;
    }

    public function registerUserAsDoctor($data)
    {
        return $this->doctorContract->create($data);
    }
}
