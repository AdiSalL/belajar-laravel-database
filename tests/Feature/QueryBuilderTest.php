<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

use function PHPUnit\Framework\assertNotNull;

class QueryBuilderTest extends TestCase
{
    public function setUp():void {
        parent::setUp();
        DB::table("products")->delete();
        DB::table("categories")->delete();
    }

    public function insertCategories() {
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
            "name" => "Handphone"
        ]);

        DB::table("categories")->insert([
            "id" => "BOOKS",
            "name" => "Knowledge"
        ]);
    }

    public function insertProducts() {
        $this->insertCategories();

        DB::table("products")->insert([
            'id' => "1",
            "name" => "Xiaomi Redmi Note 8 Pro",
            "category_id" => "SMARTPHONE",
            "price" => 2000000,
        ]);

        DB::table("products")->insert([
            'id' => "2",
            "name" => "Xiaomi Redmi Note 9 Pro",
            "category_id" => "SMARTPHONE",
            "price" => 2500000,
        ]);
    }

    public function testQueryBuilderJoin() {
        $this->insertProducts();

        $collection = DB::table("products")
        ->join("categories", "products.category_id", "=", "categories.id")
        ->select("products.id", "products.name", "products.price", "categories.name as category_name")
        ->get();
        
        self::assertCount(2, $collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }

    public function testOrdering() {
        $this->insertProducts();

        $collection = DB::table("products")->whereNotNull("id")
        ->orderBy("name", "asc")->get();
        
        self::assertCount(2, $collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }

    public function testPaging() {
        $this->insertCategories();

        $collection = DB::table("categories")
        ->skip(0)
        ->take(1)
        ->get();

        self::assertCount(1, $collection);
        $collection->each(function ($item){
            Log::info(json_encode($item));
        });
    }

    public function insertManyCategories() {
        for ($i = 0; $i < 100; $i++) {
            DB::table("categories")->insert([
                    "id" => "categories - $i",
                    "name" => "name - $i"
                ]);
        }
    }

    public function testQueryBuilderChunkResults(){
        $this->insertManyCategories();

        DB::table("categories")->orderBy("id")
        ->chunk(10, function ($categories){
            Log::info("Start Chunk");
            $this->assertNotNull($categories);
            $categories->each(function ($category) {
                Log::info(json_encode($category));
            });
            Log::info("End Chunk");
        });
    }


    // public function testUpdate() {
    //     $this->insertCategories();

    //     DB::table("categories")->where("id", "=", "GADGET")->update([
    //         "name" => "Xiaomi"
    //     ]);

    //     $collection = DB::table("categories")->where("name", "=", "Handphone")->get();
    //     self::assertCount(1, $collection);
    //     $collection->each(function ($item) {
    //         Log::info(json_encode($item));
    //     });
    // }

    // public function testUpSert() {
    //     DB::table("categories")->updateOrInsert([
    //         'id' => "VOUCHER"
    //     ], [
    //         "name" => "voucher",
    //         "description" => "Ticket and Voucher"
    //     ]);

    //     $collection = DB::table("categories")->where("id", "=", "VOUCHER")->get();
    //     self::assertCount(1, $collection);
    //     $collection->each(function ($item) {
    //         Log::info(json_encode($item));
    //     });
    // }

    // public function testIncrement() {
    //     DB::table("counter")->where("id", "=", 1)->increment("counter", 1);

    //     $collection = DB::table("counter")->where("id", "=", 1)->get();
    //     self::assertCount(1, $collection);
    //     $collection->each(function ($item) {
    //         Log::info(json_encode($item));
    //     });
    // }

    // public function testQueryBuilderWhere() {
    //     $this->insertCategories();
    //     $collection = DB::table("categories")->where("id","=", "GADGET")->get();
    //     $this->assertCount(1, $collection);
    //     $collection->each(function ($item) {
    //         Log::info(json_encode($item));
    //     });
    // }

    // public function testDelete() {
    //     $this->insertCategories();
    //     DB::table("categories")->where("id", "=", "GADGET")->delete();

    //     $collection = DB::table("categories")->where("id", "=", "GADGET")->get();
    //     $collection2 = DB::table("categories")->where("id", "=", "OTOMOTIF")->get();

    //     self::assertCount(0, $collection);
    //     self::assertCount(1, $collection2);
        
    // }





}
