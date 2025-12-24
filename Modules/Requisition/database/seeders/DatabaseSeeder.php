<?php

namespace Modules\Requisition\Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
      dd('Requisition DatabaseSeeder running');
        $this->call([
            BankSeeder::class,
        ]);
    }
}
