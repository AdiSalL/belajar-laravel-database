<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class QueryBuilderTest extends TestCase
{
    public function setUp():void {
        parent::setUp();
        DB::table("categories")->truncate();
    }

    public function insertCategories() {
        DB::table("categories")->insert([
            "id" => "GADGET",
            "name" => "Tablet"
        ]);
        
        DB::table("categories")->insert([
            "id" => "OTOMOTIF",
            "name" => "RX - King"
        ]);
        
        DB::table("categories")->insert([
            "id" => "SMARTPHONE",
            "name" => "Handphone"
        ]);

        DB::table("categories")->insert([
            "id" => "BOOKS",
            "name" => "Stoicism"
        ]);
    }

    public function testUpdate() {
        $this->insertCategories();

        DB::table("categories")->where("id", "=", "GADGET")->update([
            "name" => "Xiaomi"
        ]);

        $collection = DB::table("categories")->where("name", "=", "Handphone")->get();
        self::assertCount(1, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }
}
