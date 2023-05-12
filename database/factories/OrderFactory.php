<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'store_id' => 2,
            'customer_id' => 10,
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'governorate' => 'damascus',
            'total' => $this->faker->numberBetween(10000, 200000),
            'platform' => 'telegram',
            'payment_method' => 'mtn_cash'

        ];
    }
}
