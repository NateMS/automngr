<?php

namespace Database\Factories;

use App\Models\Contract;
use App\Models\Car;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\InsuranceType;

class ContractFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contract::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sold_at' => $this->faker->date(),
            'sell_price' => $this->faker->numberBetween(1500, 35000),
            'contact_id' => $this->faker->numberBetween(1, Contact::count()),
            'car_id' => $this->faker->unique()->numberBetween(1, Car::count()),
            'insurance_type' => (string)InsuranceType::getRandomValue(),
        ];
    }
}
