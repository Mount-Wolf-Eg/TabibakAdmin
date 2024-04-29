<?php

namespace App\Models;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;
    public float $app_payment_percentage;

    public static function group(): string
    {
        return 'general';
    }

    public static function getSettingValue($key)
    {
        return app(self::class)->$key;
    }

}
