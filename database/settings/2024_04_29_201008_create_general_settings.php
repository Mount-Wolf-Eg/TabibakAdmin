<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'Tabibak');
        $this->migrator->add('general.app_payment_percentage', .05);
        $this->migrator->add('general.urgent_grace_period', 0);
        $this->migrator->add('general.normal_grace_period', 0);
        $this->migrator->add('general.tax_percentage', 0);
    }
};
