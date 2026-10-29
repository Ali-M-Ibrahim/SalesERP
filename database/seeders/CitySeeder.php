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

        $data = [
            [
                'name' => 'Tripoli'
            ],
            [
                'name' => 'Akkar'
            ],
            [
                'name' => 'Anfeh'
            ],
            [
                'name' => 'Jbeil'
            ],
            [
                'name' => 'Zouk'
            ],
            [
                'name' => 'Dbayeh'
            ],
            [
                'name' => 'Dora'
            ],
            [
                'name' => 'Dekwaneh'
            ],
            [
                'name' => 'Mkalles'
            ],
            [
                'name' => 'Sad El Bouchrieh'
            ],
            [
                'name' => 'Sin El Fil'
            ],
            [
                'name' => 'Beirut'
            ],
            [
                'name' => 'Ouzai'
            ],
            [
                'name' => 'Choueifat'
            ],
            [
                'name' => 'Khaldeh'
            ],
            [
                'name' => 'Jiyeh'
            ],
            [
                'name' => 'Saida'
            ],
            [
                'name' => 'Nabatieh'
            ],
            [
                'name' => 'Tyre'
            ],
            [
                'name' => 'Baalbek'
            ],
            [
                'name' => 'West Bekaa'
            ],
            [
                'name' => 'Zahle'
            ],
            [
                'name' => 'Bchamoun'
            ],
            [
                'name' => 'Aramoun'
            ],
        ];

        foreach ($data as $sample) {
            City::create($sample);
        }
    }
}
