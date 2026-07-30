<?php

namespace Database\Seeders;

use App\Models\Ward;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ward::create([
            'ward_id' => 'MW',
            'description' => 'Medical Ward',
            'dept_nr' => '01',
        ]);
    }
}
