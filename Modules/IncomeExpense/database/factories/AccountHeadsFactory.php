<?php

namespace Modules\IncomeExpense\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\IncomeExpense\Models\AccountHeads;

class AccountHeadsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = AccountHeads::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'head_category'     => $this->faker->numberBetween(1, 2), 
            'account_head_name' => $this->faker->unique()->name,
            'parent_id'         => $this->faker->randomElement([0, AccountHeads::factory()]),
            'status'            => $this->faker->numberBetween(0, 1), 
            'created_by'        => $this->faker->numberBetween(1, 1000), 
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ];
    }
}
