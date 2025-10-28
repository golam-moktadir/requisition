<?php

namespace Modules\IncomeExpense\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\IncomeExpense\Models\AccountHeads;

class IncomeExpenseDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);

        AccountHeads::truncate(); 
        AccountHeads::factory()->count(10)->create();
    }
}
