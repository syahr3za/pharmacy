<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i=0; $i < 5 ; $i++) { 
            $supplier = new Supplier;

            $supplier->name = $faker->name;
            $supplier->phone_number = '0821'.$faker->randomNumber(8);
            $supplier->address = $faker->address;
            $supplier->email = $faker->email;

            $supplier->save();
        }
    }
}
