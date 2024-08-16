<?php

namespace Tests\Feature;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

use function PHPSTORM_META\map;

class QueryBuilderTest extends TestCase
{
    public function setUp():void {
        parent::setUp();
        DB::table("products")->delete();
        DB::table("categories")->delete();
    }

    public function insertCategories() {
        DB::table('categories')->insert([
            "id" => "SMARTPHONE",
            "name" => "Smartphone X",
        ]);

        DB::table('categories')->insert([
            "id" => "PROCESSOR",
            "name" => "CPU A",
        ]);
        
        DB::table('categories')->insert([
            "id" => "VGA",
            "name" => "GRAPIHCS CARD RTX",
        ]);
        
        DB::table('categories')->insert([
            "id" => "BESEK",
            "name" => "Besex X",
        ]);
        
        DB::table('categories')->insert([
            "id" => "MONITOR",
            "name" => "Monitor X",
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

    // public function testWhereDate() {
    //     $this->insertCategories();
    //     $collection = DB::table("categories")->whereDate("created_at", "2024-08-14 09:58:39")->get();
    //     $this->assertEquals(1, $collection);
    //     $collection->each(function ($item) {
    //         Log::info(json_encode($item));
    //     });
    // }

    public function testWhere() {
        $this->insertCategories();
        $result = DB::table('categories')->where(function (QueryBuilder $builder) {
            $builder->where("id", "=", "SMARTPHONE")
                    ->orWhere("id", "=", "PROCESSOR");
        })->get(); // Add get() or any method that executes the query
        
        $this->assertCount(2, $result);
        $result->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testWhereBetween() {
        $this->insertCategories();

        $result = DB::table("categories")->whereNull("description")->get();
        self::assertCount(5, $result);
        $result->each(function($item) {
            Log::info(json_encode($item));
        });

    }

    public function testInsert() {
        DB::table("categories")->insert([
            "id" => "GADGET",
            "name" => "Gadget",
        ]);
        
        DB::table("categories")->insert([
            "id" => "FOOD",
            "name" => "Food",
        ]);

        $result = DB::select("select count(id) as total from categories");
        self::assertEquals(2, $result[0]->total);
    }

    public function testSelect() {
        $this->testInsert();

        $collection = DB::table("categories")->select(["id", "name"])->get();
        $this->assertNotNull($collection);

        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function insertManyCategories() {
        for($i = 0; $i < 100; $i++) {
            DB::table('categories')->insert([
                "id" => "CATEGORY_" . $i,
                "name" => "Category " . $i,
                "description" => "Description " . $i,
            ]);
        };
    }

    public function testLazy() {
        $this->insertManyCategories();
        
        $collection = DB::table("categories")
        ->orderBy("id")->lazy(100);

        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testCursor() {
        $this->insertManyCategories();
        
        $collection = DB::table("categories")
        ->orderBy("id")->cursor();

        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testAggregate() {
        $this->insertProducts();

        $collection = DB::table('products')->count("id");
        self::assertEquals(2, $collection);
        
        
        $collection = DB::table('products')->min("price");
        self::assertEquals(2000000, $collection);
        
        
        $collection = DB::table('products')->max("price");
        self::assertEquals(2500000, $collection);

        // $collection = DB::table('products')->avg("price");
        // self::assertEquals(2500, $collection);

        
        $collection = DB::table('products')->sum("price");
        self::assertEquals(4500000, $collection);

        

    }

    


}
