<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\Contract;
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
            'documentable_id' => $this->faker->numberBetween(1, Contract::count()),
            'documentable_type' => 'App\Models\Contract',
            'type' => (string)DocumentType::getRandomValue(),
        ];
    }
}
