<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Currency::create([
            'name' => ['en' => 'Saudi Riyal', 'ar' => 'الريال السعودي'],
            'code' => 'SAR',
            'symbol' => 'ر.س',
            'is_active' => true,
        ]);
        Currency::create([
            'name' => ['en' => 'US Dollar', 'ar' => 'الدولار الأمريكي'],
            'code' => 'USD',
            'symbol' => '$',
            'is_active' => false,
        ]);
        Currency::create([
            'name' => ['en' => 'Euro', 'ar' => 'اليورو'],
            'code' => 'EUR',
            'symbol' => '€',
            'is_active' => false,
        ]);
        Currency::create([
            'name' => ['en' => 'Pound Sterling', 'ar' => 'الجنيه الإسترليني'],
            'code' => 'GBP',
            'symbol' => '£',
            'is_active' => false,
        ]);

    }
}
