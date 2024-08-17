<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

            DB::table("categories")->insert([
                "id" => "GADGET",
                "name" => "Tablet"
            ]);
            DB::table("categories")->insert([
                "id" => "OTOMOTIF",
                "name" => "Motor Bicycle"
            ]);
            
            DB::table("categories")->insert([
                "id" => "SMARTPHONE",
                "name" => "Smartphone X",
            ]);
    
            DB::table("categories")->insert([
                "id" => "BOOKS",
                "name" => "Knowledge"
            ]);
      
    }
}
