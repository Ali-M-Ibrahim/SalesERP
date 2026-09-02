<?php

namespace Database\Seeders;

use App\Models\Resource;
use App\Models\ResourceCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::role('admin')->first();

        $data = [
            'Catalogues' => [
                [
                    'name' => '2026 Full Product Catalogue',
                    'description' => 'Complete digital catalogue containing the full product range.',
                    'file_type' => 'pdf',
                ],
                [
                    'name' => 'Facade Solutions Catalogue',
                    'description' => 'Catalogue containing facade products, finishes and solutions.',
                    'file_type' => 'pdf',
                ],
            ],

            'Datasheets' => [
                [
                    'name' => 'ACP Technical Datasheet',
                    'description' => 'Technical specifications and performance information for ACP panels.',
                    'file_type' => 'pdf',
                ],
                [
                    'name' => 'Sandwich Panel Technical Datasheet',
                    'description' => 'Technical information for sandwich panels including available thicknesses.',
                    'file_type' => 'pdf',
                ],
            ],

            'Product Images' => [
                [
                    'name' => 'ACP Silver Metallic',
                    'description' => 'High resolution image of the silver metallic ACP panel.',
                    'file_type' => 'image',
                ],
                [
                    'name' => 'ACP Wood Grain',
                    'description' => 'High resolution image of the wood grain ACP panel.',
                    'file_type' => 'image',
                ],
            ],

            'Certificates' => [
                [
                    'name' => 'ACP Fire Rating Certificate',
                    'description' => 'Fire rating certification document for ACP products.',
                    'file_type' => 'pdf',
                ],
            ],

            'Presentations' => [
                [
                    'name' => 'Company Product Presentation',
                    'description' => 'Sales presentation covering the company and main product ranges.',
                    'file_type' => 'pdf',
                ],
            ],
        ];

        foreach ($data as $categoryName => $resources) {

            $category = ResourceCategory::firstOrCreate(
                [
                    'name' => $categoryName,
                ],
                [
                    'id' => (string) Str::uuid(),
                    'sort_order' => 0,
                    'is_active' => true,
                ]
            );

            foreach ($resources as $resource) {

                Resource::firstOrCreate(
                    [
                        'name' => $resource['name'],
                        'resource_category_id' => $category->id,
                    ],
                    [
                        'id' => (string) Str::uuid(),

                        'description' => $resource['description'],

                        /*
                         * We are not attaching actual files
                         * in the development seeder.
                         */
                        'file_path' => null,
                        'thumbnail_path' => null,

                        'file_type' => $resource['file_type'],
                        'file_size' => null,

                        'added_by' => $admin?->id,

                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
