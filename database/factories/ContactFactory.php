<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Provider\de_CH\PhoneNumber;
use Faker\Provider\en_US\Person;
use Faker\Provider\en_US\Address;
use Faker\Provider\en_US\Company;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'phone' => $this->faker->PhoneNumber(),
            'email' => $this->faker->email(),
            'address' => $this->faker->streetName() . ' ' . $this->faker->buildingNumber(),
            'zip' => $this->faker->randomNumber(4, true),
            'city' => $this->faker->city(),
            'country' => 'CH',
            'company' => $this->faker->company(),
            'notes' => $this->faker->text(),
        ];
    }
}
