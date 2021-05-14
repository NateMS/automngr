<?php

namespace Database\Factories;

use App\Models\CarPayment;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\PaymentType;
use App\Models\SellContract;

class CarPaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CarPayment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'amount' => $this->faker->numberBetween(1000, 10000),
            'date' => $this->faker->date(),
            'type' => (string)PaymentType::getRandomValue(),
            'sell_contract_id' => $this->faker->numberBetween(1, SellContract::count()),
        ];
    }
}
