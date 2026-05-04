<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Companies;

class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            ['name' => 'TechCompany 1'],
            ['name' => 'TechCompany 2'],
        ];

        foreach ($companies as $company) {
            $newCompany = Companies::updateOrCreate($company);
        }
    }
}
