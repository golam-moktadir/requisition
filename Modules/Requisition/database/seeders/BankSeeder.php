<?php

namespace Modules\Requisition\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Requisition\Models\Bank;

class BankSeeder extends Seeder
{
    public function run()
    {
        Bank::factory()->count(400)->create();
    }
}
