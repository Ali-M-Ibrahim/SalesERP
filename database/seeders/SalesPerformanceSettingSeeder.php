<?php

namespace Database\Seeders;

use App\Models\SalesPerformanceSetting;
use Illuminate\Database\Seeder;

class SalesPerformanceSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [

            [
                'name' => 'completion',
                'label' => 'Visit Completion',
                'weight' => 30,
                'is_active' => true,
                'sort_order' => 10,
            ],

            [
                'name' => 'coverage',
                'label' => 'Customer Coverage',
                'weight' => 30,
                'is_active' => true,
                'sort_order' => 20,
            ],

            [
                'name' => 'satisfaction',
                'label' => 'Customer Satisfaction',
                'weight' => 25,
                'is_active' => true,
                'sort_order' => 30,
            ],

            [
                'name' => 'gps_verification',
                'label' => 'GPS Verification',
                'weight' => 15,
                'is_active' => true,
                'sort_order' => 40,
            ],
        ];


        foreach ($settings as $setting) {

            SalesPerformanceSetting::updateOrCreate(
                [
                    'name' => $setting['name'],
                ],
                $setting
            );
        }
    }
}
