<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $citiesJson = file_get_contents(public_path('json/cities.json'));
        $citiesData = json_decode($citiesJson, true);
        foreach ($citiesData as $cityData) {
            $city = new City();
            $city->region_id = $cityData['region_id'];
            $city->name = [
                'en' => $cityData['en'],
                'ar' => $cityData['ar'],
            ];
            $city->save();
        }
    }
}
