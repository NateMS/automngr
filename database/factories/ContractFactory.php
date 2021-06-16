<?php

namespace Database\Factories;

use App\Models\Contract;
use App\Models\Car;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\InsuranceType;
use App\Enums\ContractType;

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
            'date' => $this->faker->date(),
            'delivery_date' => $this->faker->date(),
            'notes' => $this->faker->word(),
            'price' => $this->faker->numberBetween(150000, 3500000),
            'contact_id' => $this->faker->numberBetween(1, Contact::count()),
            'car_id' => $this->faker->numberBetween(1, Car::count()),
            'insurance_type' => (string)InsuranceType::getRandomValue(),
            'type' => (string)ContractType::getRandomValue(),
        ];
    }
}
