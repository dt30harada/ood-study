<?php

namespace Database\Seeders;

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
        $this->call([
            master\CustomerRanksSeeder::class,
            master\CustomersSeeder::class,
            master\ItemCategoriesSeeder::class,
            master\ItemsSeeder::class,
        ]);
    }
}
