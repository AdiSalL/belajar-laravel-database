<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RawQueryTest extends TestCase
{   
    public function setUp(): void {
        parent::setUp();
        DB::delete("DELETE FROM categories");
    }

    public function testCrud() {
        DB::insert("INSERT INTO categories(id, name, description) VALUES (:id, :name , :description)", [
            "id" => "GADGET", 
            "name" => "Gadget", 
            "description" => "Gadget Category"
        ]);

        $results = DB::select("SELECT * FROM categories WHERE id = ?", ["GADGET"]);
        $this->assertCount(1, $results);
        $this->assertEquals("GADGET", $results[0]->id);
        $this->assertEquals("Gadget", $results[0]->name);
        $this->assertEquals("Gadget Category", $results[0]->description);
        
    }
}
