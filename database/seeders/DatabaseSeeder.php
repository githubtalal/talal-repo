<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(StoreSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(EcartStoreSeeder::class);
        $this->call(TestStoreSeeder::class);
        $this->call(TranslationSeeder::class);
        //$this->call(PriceOrderItemsSeeder::class);
    }
}
