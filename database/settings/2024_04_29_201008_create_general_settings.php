<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'jj2.png');
        $this->migrator->add('general.app_payment_percentage', .05);
    }
};
