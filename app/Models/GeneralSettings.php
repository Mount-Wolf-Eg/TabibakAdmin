<?php

namespace App\Models;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;
    public float $app_payment_percentage;
    public $featured_list_title;
    public $featured_list_text;

    public float $reschedule_grace_period;
    public float $cancel_grace_period;

    public float $urgent_grace_period;
    public float $normal_grace_period;
    public float $tax_percentage;

    public static function group(): string
    {
        return 'general';
    }

    public static function getSettingValue($key)
    {
        return app(self::class)->$key;
    }
}
