<?php

namespace App\Providers;

use App\Services\Repositories\ConsultationDoctorReferralService;
use App\Services\Repositories\UserAuthService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        foreach ($this->getModels() as $model) {
            $this->app->bind(
                "App\Repositories\Contracts\\{$model}Contract",
                "App\Repositories\SQL\\{$model}Repository"
            );
        }
        $this->app->bind(UserAuthService::class, function ($app) {
            return new UserAuthService(
                $app->make('App\Repositories\Contracts\UserContract'),
                $app->make('App\Repositories\Contracts\PatientContract'),
                $app->make('App\Repositories\Contracts\DoctorContract'),
            );
        });
        $this->app->bind('App\Services\Repositories\ConsultationDoctorReferralService', function ($app) {
            return new ConsultationDoctorReferralService(
                $app->make('App\Repositories\Contracts\ConsultationContract'),
            );
        });
    }

    protected function getModels(): Collection
    {
        $files = Storage::disk('app')->files('Models');
        return collect($files)->map(function ($file) {
            return basename($file, '.php');
        });
    }
}
