<?php

namespace Database\Seeders;

use App\Models\Sample;
use App\Models\SampleCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SampleSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'ACP' => [
                [
                    'code' => 'ACP-SILVER',
                    'name' => 'ACP Silver Metallic Panel',
                    'description' => 'Silver metallic aluminium composite panel sample.',
                ],
                [
                    'code' => 'ACP-WOOD',
                    'name' => 'ACP Wood Grain Panel',
                    'description' => 'Wood grain aluminium composite panel sample.',
                ],
            ],

            'Sandwich Panels' => [
                [
                    'code' => 'SP-50-PIR',
                    'name' => 'Sandwich Panel 50mm PIR',
                    'description' => '50mm PIR insulated sandwich panel sample.',
                ],
                [
                    'code' => 'SP-80-PIR',
                    'name' => 'Sandwich Panel 80mm PIR',
                    'description' => '80mm PIR insulated sandwich panel sample.',
                ],
            ],

            'HPL' => [
                [
                    'code' => 'HPL-MATTE',
                    'name' => 'HPL Compact Panel Matte',
                    'description' => 'Matte finish HPL compact panel sample.',
                ],
                [
                    'code' => 'HPL-WOOD',
                    'name' => 'HPL Compact Panel Wood',
                    'description' => 'Wood finish HPL compact panel sample.',
                ],
            ],

            'Insulation' => [
                [
                    'code' => 'INS-BOARD',
                    'name' => 'Insulation Board Sample',
                    'description' => 'Thermal insulation board sample.',
                ],
            ],

            'Reference' => [
                [
                    'code' => 'COLOR-SWATCH',
                    'name' => 'Colour Swatch Book',
                    'description' => 'Product colour and finish reference book.',
                ],
            ],

            'Hardware' => [
                [
                    'code' => 'FASTENER-KIT',
                    'name' => 'Fastener & Trim Kit',
                    'description' => 'Sample kit containing common fasteners and trims.',
                ],
            ],
        ];

        foreach ($data as $categoryName => $samples) {

            $category = SampleCategory::firstOrCreate(
                [
                    'name' => $categoryName,
                ],
                [
                    'id' => (string) Str::uuid(),
                    'is_active' => true,
                ]
            );

            foreach ($samples as $sample) {

                Sample::firstOrCreate(
                    [
                        'code' => $sample['code'],
                    ],
                    [
                        'id' => (string) Str::uuid(),
                        'sample_category_id' => $category->id,
                        'name' => $sample['name'],
                        'description' => $sample['description'],
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
