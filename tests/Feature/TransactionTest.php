<?php

namespace Tests\Feature;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Unique;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    public function setUp():void {
        parent::setUp();

        DB::delete("DELETE FROM categories");
    }

    public function testTransactionSuccess() {
        DB::transaction(function () {
                DB::insert("INSERT INTO categories(id, name, description) VALUES (:id, :name , :description)", [
                    "id" => "GADGET", 
                    "name" => "Gadget", 
                    "description" => "Gadget Category"
                ]);

                DB::insert("INSERT INTO categories(id, name, description) VALUES (:id, :name , :description)", [
                    "id" => "FOOD", 
                    "name" => "Food", 
                    "description" => "Food Category"
                ]);
        }, 2);
        $result = DB::select("SELECT * FROM categories");
        self::assertCount(2, $result);
    
    }

    public function testTransactionFailed() {
        try {
            DB::transaction(function () {
                DB::insert("INSERT INTO categories(id, name, description) VALUES (:id, :name , :description)", [
                    "id" => "GADGET", 
                    "name" => "Gadget", 
                    "description" => "Gadget Category"
                ]);

                DB::insert("INSERT INTO categories(id, name, description) VALUES (:id, :name , :description)", [
                    "id" => "GADGET", 
                    "name" => "Gadget", 
                    "description" => "Gadget Category"
                ]);
        }, 2);
        }catch(QueryException $e) {
            return $e->getMessage();
        }

        $result = DB::select("SELECT * FROM categories");
        self::assertCount(0, $result);
    
    }

    public function testManualTransactionFailed() {
        try {
            DB::beginTransaction();
            DB::insert("INSERT INTO categories(id, name, description) VALUES (:id, :name , :description)", [
                "id" => "GADGET", 
                "name" => "Gadget", 
                "description" => "Gadget Category"
            ]);

            DB::insert("INSERT INTO categories(id, name, description) VALUES (:id, :name , :description)", [
                "id" => "GADGET", 
                "name" => "Gadget", 
                "description" => "Gadget Category"
            ]);
            DB::commit();
        }catch(QueryException $e) {
            DB::rollBack();
        }

        $result = DB::select("SELECT * FROM categories");
        self::assertCount(0, $result);
    
    }

    public function testManualTransactionSuccess() {
        try {
            DB::beginTransaction();
            DB::insert("INSERT INTO categories(id, name, description) VALUES (:id, :name , :description)", [
                "id" => "GADGET", 
                "name" => "Gadget", 
                "description" => "Gadget Category"
            ]);

            DB::insert("INSERT INTO categories(id, name, description) VALUES (:id, :name , :description)", [
                "id" => "FOOD", 
                "name" => "Food", 
                "description" => "Food Category"
            ]);
            DB::commit();
        }catch(QueryException $e) {
            DB::rollBack();
        }

        $result = DB::select("SELECT * FROM categories");
        self::assertCount(2, $result);
    
    }
}
