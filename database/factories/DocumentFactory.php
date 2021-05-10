<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\DocumentType;


class DocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Document::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'car_id' => $this->faker->numberBetween(1, Car::count()),
            'document_type' => (string)DocumentType::getRandomValue(),
        ];
    }
}
