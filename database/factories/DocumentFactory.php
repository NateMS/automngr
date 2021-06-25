<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;


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
            'name' => 'Vertrag.pdf',
            'internal_name' => '2021-06-11-13:11:12.pdf',
            'documentable_id' => $this->faker->numberBetween(1, Contract::count()),
            'documentable_type' => 'contracts',
            'size' => $this->faker->numberBetween(1, 30000),
            'extension' => 'pdf',
        ];
    }
}
