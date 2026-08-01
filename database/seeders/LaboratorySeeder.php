<?php

namespace Database\Seeders;

use App\Models\Laboratory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LaboratorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Laboratory::create([
            'code' => 'AA2',
            'test' => 'AA2',
            'section_code' => 'AA',
            'section' => 'AA',
            'opd' => 'AA2',
            'ipd' => 'AA2',
            
        ]);
    }
}
