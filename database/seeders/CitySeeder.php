<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            ['name' => 'دمشق'],
            ['name' => 'ريف دمشق'],
            ['name' => 'حلب'],
            ['name' => 'حمص'],
            ['name' => 'حماة'],
            ['name' => 'اللاذقية'],
            ['name' => 'طرطوس'],
            ['name' => 'إدلب'],
            ['name' => 'دير الزور'],
            ['name' => 'الرقة'],
            ['name' => 'الحسكة'],
            ['name' => 'درعا'],
            ['name' => 'السويداء'],
            ['name' => 'القنيطرة'],
        ];

        foreach ($cities as $city) {
            City::create($city);
        }
    }
}
