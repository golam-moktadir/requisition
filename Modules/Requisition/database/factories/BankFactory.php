<?php

namespace Modules\Requisition\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Requisition\Models\Bank;

class BankFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Bank::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bank_name'      => $this->faker->company . ' Bank',
            'branch_name'    => $this->faker->city . ' Branch',
            'branch_address' => $this->faker->address,
        ];
    }
}
