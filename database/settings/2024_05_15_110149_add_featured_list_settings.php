<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.featured_list_title', '{"en":"","ar":""}');
        $this->migrator->add('general.featured_list_text', '{"en":"","ar":""}');
    }
};
