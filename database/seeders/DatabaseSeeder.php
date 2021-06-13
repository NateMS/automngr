<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Team;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\Brand;
use App\Models\Payment;
use App\Models\Contract;
use App\Models\Contact;
use App\Models\Document;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Payment::truncate();
        Contract::truncate();
        Document::truncate();
        Car::truncate();
        Contact::truncate();
        CarModel::truncate();
        Brand::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $user = User::factory()->create([
            'name' => 'Nadim Salloum',
            'email' => 'hello@salloum.ch',
            'password' => bcrypt('abc123'),
        ]);

        $team = Team::factory()->create([
            'name' => 'Your SwissCar GmbH',
            'user_id' => $user->id,
            'personal_team' => false,
        ]);

        foreach ($this->getBrands() as $brand) {
            Brand::create(['name' => $brand]);
        }

        $carModels = CarModel::factory()
            ->count(350)
            ->create();
        
        $contacts = Contact::factory()
            ->count(60)
            ->create();

        $nOfCars = 75;
        $cars = Car::factory()
            ->count($nOfCars)
            ->create();

        $contracts = Contract::factory()
            ->count($nOfCars * 2)
            ->create();

        $payments = Payment::factory()
            ->count(60)
            ->create();

        $documents = Document::factory()
            ->count(40)
            ->create();
    }

    public function getBrands(): array
    {
        return [
            "Abarth",
            "Alfa Romeo",
            "Aston Martin",
            "Audi",
            "Bentley",
            "BMW",
            "Bugatti",
            "Cadillac",
            "Chevrolet",
            "Chrysler",
            "Citroën",
            "Dacia",
            "Daewoo",
            "Daihatsu",
            "Dodge",
            "Donkervoort",
            "DS",
            "Ferrari",
            "Fiat",
            "Fisker",
            "Ford",
            "Honda",
            "Hummer",
            "Hyundai",
            "Infiniti",
            "Iveco",
            "Jaguar",
            "Jeep",
            "Kia",
            "KTM",
            "Lada",
            "Lamborghini",
            "Lancia",
            "Land Rover",
            "Landwind",
            "Lexus",
            "Lotus",
            "Maserati",
            "Maybach",
            "Mazda",
            "McLaren",
            "Mercedes-Benz",
            "MG",
            "Mini",
            "Mitsubishi",
            "Morgan",
            "Nissan",
            "Opel",
            "Peugeot",
            "Porsche",
            "Renault",
            "Rolls-Royce",
            "Rover",
            "Saab",
            "Seat",
            "Skoda",
            "Smart",
            "SsangYong",
            "Subaru",
            "Suzuki",
            "Tesla",
            "Toyota",
            "Volkswagen",
            "Volvo"
        ];
    }

}
