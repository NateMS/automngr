<?php

namespace Database\Factories;

use App\Models\BuyContract;
use App\Models\Car;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class BuyContractFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BuyContract::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date' => $this->faker->date(),
            'price' => $this->faker->numberBetween(150000, 3500000),
            'contact_id' => $this->faker->numberBetween(1, Contact::count()),
            'car_id' => $this->faker->unique()->numberBetween(1, Car::count()),
        ];
    }
}
