<?php

namespace Database\Seeders;

use App\Models\Radiology;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RadiologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Radiology::create([
            'code' => 'XR0001',
            'test' => 'ABDOMEN PLAIN',
            'group_code' => 'XRY001',
            'group' => 'XRAY',
            'section_code' => '164',
            'section' => 'XRAY',
        ]);

    }
}
