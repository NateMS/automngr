<?php

namespace Database\Factories;

use App\Enums\PaymentType;
use App\Models\Contract;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

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
            'type' => (string) PaymentType::getRandomValue(),
            'contract_id' => $this->faker->numberBetween(1, Contract::count()),
        ];
    }
}
