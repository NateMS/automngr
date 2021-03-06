<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\CarModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CarModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'brand_id' => $this->faker->numberBetween(1, Brand::count()),
        ];
    }
}
