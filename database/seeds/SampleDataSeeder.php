<?php

use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'name' => 'Eleaf 40w TC',
                'sku' => 'E40TC',
                'category' => 'regulated_mod',
                'cost' => '10.00'
            ],
        ]);
        DB::table('product_instances')->insert([
            [
                'product_id' => 1,
                'price' => '39.99',
                'stock' => '6',
                'redline' => '5',
                'active' => 1,
                'store' => 1
            ],
            [
                'product_id' => 1,
                'price' => '39.99',
                'stock' => '6',
                'redline' => '5',
                'active' => 1,
                'store' => 2
            ],
            [
                'product_id' => 1,
                'price' => '39.99',
                'stock' => '6',
                'redline' => '5',
                'active' => 1,
                'store' => 3
            ],
        ]);
        DB::table('ingredients')->insert([
            [
                'name' => 'Blueberry Wild',
                'vendor' => 'TPA',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ],
            [
                'name' => 'Strawberry',
                'vendor' => 'TPA',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ],
            [
                'name' => 'Watermelon',
                'vendor' => 'TPA',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ],
        ]);
        DB::table('recipes')->insert([
            [
                'name' => 'Berry Blast',
                'active' => 1,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ],
        ]);
        DB::table('recipe_ingredient')->insert([
            [
                'recipe_id' => 1,
                'ingredient_id' => 1,
                'amount' => 10,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ],
            [
                'recipe_id' => 1,
                'ingredient_id' => 2,
                'amount' => 10,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ],
        ]);
        DB::table('customers')->insert([
            [
                'name' => 'John Doe',
                'phone' => 1234567890,
                'email' => 'john@gmail.com',
                'points' => 500,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ],
            [
                'name' => 'Jim Jones',
                'phone' => 9876543210,
                'email' => 'jim@hotmail.com',
                'points' => 500,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ],
        ]);
        DB::table('discounts')->insert([
            [
                'name' => '10% Off',
                'type' => 'percent',
                'filter' => 'none',
                'amount' => 10,
                'approval' => 0,
                'redeemable' => false,
                'value' => null,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ],
            [
                'name' => '50% Off Eliquid',
                'type' => 'percent',
                'filter' => 'liquid',
                'amount' => 50,
                'approval' => 1,
                'redeemable' => false,
                'value' => null,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ],
            [
                'name' => '10ml Discount',
                'type' => 'amount',
                'filter' => 'liquid',
                'amount' => 4.49,
                'approval' => 1,
                'redeemable' => true,
                'value' => 100,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ],
            [
                'name' => '30ml Discount',
                'type' => 'amount',
                'filter' => 'liquid',
                'amount' => 11.49,
                'approval' => 1,
                'redeemable' => true,
                'value' => 200,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ],
            [
                'name' => '50ml Discount',
                'type' => 'amount',
                'filter' => 'liquid',
                'amount' => 18.49,
                'approval' => 1,
                'redeemable' => true,
                'value' => 300,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ],
            [
                'name' => '100ml Discount',
                'type' => 'amount',
                'filter' => 'liquid',
                'amount' => 34.49,
                'approval' => 1,
                'redeemable' => true,
                'value' => 600,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ],
        ]);

    }
}
