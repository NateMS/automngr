<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\Brand;
use App\Models\CarPayment;
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
        CarPayment::truncate();
        Contract::truncate();
        Document::truncate();
        Car::truncate();
        Contact::truncate();
        CarModel::truncate();
        Brand::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    
        foreach ($this->getBrands() as $brand) {
            Brand::create(['brand' => $brand]);
        }

        $carModels = CarModel::factory()
            ->count(350)
            ->create();
        
        $contacts = Contact::factory()
            ->count(100)
            ->create();

        $cars = Car::factory()
            ->count(40)
            ->create();

        $contracts = Contract::factory()
            ->count(20)
            ->create();

        $carPayments = CarPayment::factory()
            ->count(15)
            ->create();

        $documents = Document::factory()
            ->count(10)
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
