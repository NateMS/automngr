<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\CarModel;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Car::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'stammnummer' => $this->faker->regexify('[0-9]{3}.[0-9]{3}.[0-9]{3}'),
            'vin' => $this->faker->regexify('[A-Z]{3}ZZZ[A-Z0-9]{3}[A-Z1-9]{1}[A-Z]{1}[0-9]{6}'),
            'colour' => $this->faker->safeColorName(),
            'notes' => $this->faker->paragraph(),
            'known_damage' => $this->faker->paragraph(),
            'initial_date' => $this->faker->date(),
            'last_check_date' => $this->faker->date(),
            'bought_at' => $this->faker->date(),
            'kilometers' => $this->faker->numberBetween(5000, 200000),
            'buy_price' => $this->faker->numberBetween(100000, 3000000),
            'seller_contact_id' => $this->faker->numberBetween(1, Contact::count()),
            'car_model_id' => $this->faker->numberBetween(1, CarModel::count()),
        ];
    }
}
