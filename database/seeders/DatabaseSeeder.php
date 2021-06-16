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

        foreach ($this->getBrands() as $brandItem) {
            $brand = Brand::create(['name' => $brandItem['brand']]);
            
            foreach($brandItem['models'] as $model) {
                CarModel::create([
                    'name' => $model,
                    'brand_id' => $brand->id,
                ]);
            }
        }
        
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
            0 => [
              'brand' => 'Seat',
              'models' => [
                0 => 'Alhambra',
                1 => 'Altea',
                2 => 'Altea XL',
                3 => 'Arosa',
                4 => 'Cordoba',
                5 => 'Cordoba Vario',
                6 => 'Exeo',
                7 => 'Ibiza',
                8 => 'Ibiza ST',
                9 => 'Exeo ST',
                10 => 'Leon',
                11 => 'Leon ST',
                12 => 'Inca',
                13 => 'Mii',
                14 => 'Toledo',
              ],
            ],
            1 => [
              'brand' => 'Renault',
              'models' => [
                0 => 'Captur',
                1 => 'Clio',
                2 => 'Clio Grandtour',
                3 => 'Espace',
                4 => 'Express',
                5 => 'Fluence',
                6 => 'Grand Espace',
                7 => 'Grand Modus',
                8 => 'Grand Scenic',
                9 => 'Kadjar',
                10 => 'Kangoo',
                11 => 'Kangoo Express',
                12 => 'Koleos',
                13 => 'Laguna',
                14 => 'Laguna Grandtour',
                15 => 'Latitude',
                16 => 'Mascott',
                17 => 'Mégane',
                18 => 'Mégane CC',
                19 => 'Mégane Combi',
                20 => 'Mégane Grandtour',
                21 => 'Mégane Coupé',
                22 => 'Mégane Scénic',
                23 => 'Scénic',
                24 => 'Talisman',
                25 => 'Talisman Grandtour',
                26 => 'Thalia',
                27 => 'Twingo',
                28 => 'Wind',
                29 => 'Zoé',
              ],
            ],
            2 => [
              'brand' => 'Peugeot',
              'models' => [
                0 => '1007',
                1 => '107',
                2 => '106',
                3 => '108',
                4 => '2008',
                5 => '205',
                6 => '205 Cabrio',
                7 => '206',
                8 => '206 CC',
                9 => '206 SW',
                10 => '207',
                11 => '207 CC',
                12 => '207 SW',
                13 => '306',
                14 => '307',
                15 => '307 CC',
                16 => '307 SW',
                17 => '308',
                18 => '308 CC',
                19 => '308 SW',
                20 => '309',
                21 => '4007',
                22 => '4008',
                23 => '405',
                24 => '406',
                25 => '407',
                26 => '407 SW',
                27 => '5008',
                28 => '508',
                29 => '508 SW',
                30 => '605',
                31 => '806',
                32 => '607',
                33 => '807',
                34 => 'Bipper',
                35 => 'RCZ',
              ],
            ],
            3 => [
              'brand' => 'Dacia',
              'models' => [
                0 => 'Dokker',
                1 => 'Duster',
                2 => 'Lodgy',
                3 => 'Logan',
                4 => 'Logan MCV',
                5 => 'Logan Van',
                6 => 'Sandero',
                7 => 'Solenza',
              ],
            ],
            4 => [
              'brand' => 'Citroën',
              'models' => [
                0 => 'Berlingo',
                1 => 'C-Crosser',
                2 => 'C-Elissée',
                3 => 'C-Zero',
                4 => 'C1',
                5 => 'C2',
                6 => 'C3',
                7 => 'C3 Picasso',
                8 => 'C4',
                9 => 'C4 Aircross',
                10 => 'C4 Cactus',
                11 => 'C4 Coupé',
                12 => 'C4 Grand Picasso',
                13 => 'C4 Sedan',
                14 => 'C5',
                15 => 'C5 Break',
                16 => 'C5 Tourer',
                17 => 'C6',
                18 => 'C8',
                19 => 'DS3',
                20 => 'DS4',
                21 => 'DS5',
                22 => 'Evasion',
                23 => 'Jumper',
                24 => 'Jumpy',
                25 => 'Saxo',
                26 => 'Nemo',
                27 => 'Xantia',
                28 => 'Xsara',
              ],
            ],
            5 => [
              'brand' => 'Opel',
              'models' => [
                0 => 'Agila',
                1 => 'Ampera',
                2 => 'Antara',
                3 => 'Astra',
                4 => 'Astra cabrio',
                5 => 'Astra caravan',
                6 => 'Astra coupé',
                7 => 'Calibra',
                8 => 'Campo',
                9 => 'Cascada',
                10 => 'Corsa',
                11 => 'Frontera',
                12 => 'Insignia',
                13 => 'Insignia kombi',
                14 => 'Kadett',
                15 => 'Meriva',
                16 => 'Mokka',
                17 => 'Movano',
                18 => 'Omega',
                19 => 'Signum',
                20 => 'Vectra',
                21 => 'Vectra Caravan',
                22 => 'Vivaro',
                23 => 'Vivaro Kombi',
                24 => 'Zafira',
              ],
            ],
            6 => [
              'brand' => 'Alfa Romeo',
              'models' => [
                0 => '145',
                1 => '146',
                2 => '147',
                3 => '155',
                4 => '156',
                5 => '156 Sportwagon',
                6 => '159',
                7 => '159 Sportwagon',
                8 => '164',
                9 => '166',
                10 => '4C',
                11 => 'Brera',
                12 => 'GTV',
                13 => 'MiTo',
                14 => 'Crosswagon',
                15 => 'Spider',
                16 => 'GT',
                17 => 'Giulietta',
                18 => 'Giulia',
              ],
            ],
            7 => [
              'brand' => 'Škoda',
              'models' => [
                0 => 'Favorit',
                1 => 'Felicia',
                2 => 'Citigo',
                3 => 'Fabia',
                4 => 'Fabia Combi',
                5 => 'Fabia Sedan',
                6 => 'Felicia Combi',
                7 => 'Octavia',
                8 => 'Octavia Combi',
                9 => 'Roomster',
                10 => 'Yeti',
                11 => 'Rapid',
                12 => 'Rapid Spaceback',
                13 => 'Superb',
                14 => 'Superb Combi',
              ],
            ],
            8 => [
              'brand' => 'Chevrolet',
              'models' => [
                0 => 'Alero',
                1 => 'Aveo',
                2 => 'Camaro',
                3 => 'Captiva',
                4 => 'Corvette',
                5 => 'Cruze',
                6 => 'Cruze SW',
                7 => 'Epica',
                8 => 'Equinox',
                9 => 'Evanda',
                10 => 'HHR',
                11 => 'Kalos',
                12 => 'Lacetti',
                13 => 'Lacetti SW',
                14 => 'Lumina',
                15 => 'Malibu',
                16 => 'Matiz',
                17 => 'Monte Carlo',
                18 => 'Nubira',
                19 => 'Orlando',
                20 => 'Spark',
                21 => 'Suburban',
                22 => 'Tacuma',
                23 => 'Tahoe',
                24 => 'Trax',
              ],
            ],
            9 => [
              'brand' => 'Porsche',
              'models' => [
                0 => '911 Carrera',
                1 => '911 Carrera Cabrio',
                2 => '911 Targa',
                3 => '911 Turbo',
                4 => '924',
                5 => '944',
                6 => '997',
                7 => 'Boxster',
                8 => 'Cayenne',
                9 => 'Cayman',
                10 => 'Macan',
                11 => 'Panamera',
              ],
            ],
            10 => [
              'brand' => 'Honda',
              'models' => [
                0 => 'Accord',
                1 => 'Accord Coupé',
                2 => 'Accord Tourer',
                3 => 'City',
                4 => 'Civic',
                5 => 'Civic Aerodeck',
                6 => 'Civic Coupé',
                7 => 'Civic Tourer',
                8 => 'Civic Type R',
                9 => 'CR-V',
                10 => 'CR-X',
                11 => 'CR-Z',
                12 => 'FR-V',
                13 => 'HR-V',
                14 => 'Insight',
                15 => 'Integra',
                16 => 'Jazz',
                17 => 'Legend',
                18 => 'Prelude',
              ],
            ],
            11 => [
              'brand' => 'Subaru',
              'models' => [
                0 => 'BRZ',
                1 => 'Forester',
                2 => 'Impreza',
                3 => 'Impreza Wagon',
                4 => 'Justy',
                5 => 'Legacy',
                6 => 'Legacy Wagon',
                7 => 'Legacy Outback',
                8 => 'Levorg',
                9 => 'Outback',
                10 => 'SVX',
                11 => 'Tribeca',
                12 => 'Tribeca B9',
                13 => 'XV',
              ],
            ],
            12 => [
              'brand' => 'Mazda',
              'models' => [
                0 => '121',
                1 => '2',
                2 => '3',
                3 => '323',
                4 => '323 Combi',
                5 => '323 Coupé',
                6 => '323 F',
                7 => '5',
                8 => '6',
                9 => '6 Combi',
                10 => '626',
                11 => '626 Combi',
                12 => 'B-Fighter',
                13 => 'B2500',
                14 => 'BT',
                15 => 'CX-3',
                16 => 'CX-5',
                17 => 'CX-7',
                18 => 'CX-9',
                19 => 'Demio',
                20 => 'MPV',
                21 => 'MX-3',
                22 => 'MX-5',
                23 => 'MX-6',
                24 => 'Premacy',
                25 => 'RX-7',
                26 => 'RX-8',
                27 => 'Xedox 6',
              ],
            ],
            13 => [
              'brand' => 'Mitsubishi',
              'models' => [
                0 => '3000 GT',
                1 => 'ASX',
                2 => 'Carisma',
                3 => 'Colt',
                4 => 'Colt CC',
                5 => 'Eclipse',
                6 => 'Fuso canter',
                7 => 'Galant',
                8 => 'Galant Combi',
                9 => 'Grandis',
                10 => 'L200',
                11 => 'L200 Pick up',
                12 => 'L200 Pick up Allrad',
                13 => 'L300',
                14 => 'Lancer',
                15 => 'Lancer Combi',
                16 => 'Lancer Evo',
                17 => 'Lancer Sportback',
                18 => 'Outlander',
                19 => 'Pajero',
                20 => 'Pajeto Pinin',
                21 => 'Pajero Pinin Wagon',
                22 => 'Pajero Sport',
                23 => 'Pajero Wagon',
                24 => 'Space Star',
              ],
            ],
            14 => [
              'brand' => 'Lexus',
              'models' => [
                0 => 'CT',
                1 => 'GS',
                2 => 'GS 300',
                3 => 'GX',
                4 => 'IS',
                5 => 'IS 200',
                6 => 'IS 250 C',
                7 => 'IS-F',
                8 => 'LS',
                9 => 'LX',
                10 => 'NX',
                11 => 'RC F',
                12 => 'RX',
                13 => 'RX 300',
                14 => 'RX 400h',
                15 => 'RX 450h',
                16 => 'SC 430',
              ],
            ],
            15 => [
              'brand' => 'Toyota',
              'models' => [
                0 => '4-Runner',
                1 => 'Auris',
                2 => 'Avensis',
                3 => 'Avensis Combi',
                4 => 'Avensis Van Verso',
                5 => 'Aygo',
                6 => 'Camry',
                7 => 'Carina',
                8 => 'Celica',
                9 => 'Corolla',
                10 => 'Corolla Combi',
                11 => 'Corolla sedan',
                12 => 'Corolla Verso',
                13 => 'FJ Cruiser',
                14 => 'GT86',
                15 => 'Hiace',
                16 => 'Hiace Van',
                17 => 'Highlander',
                18 => 'Hilux',
                19 => 'Land Cruiser',
                20 => 'MR2',
                21 => 'Paseo',
                22 => 'Picnic',
                23 => 'Prius',
                24 => 'RAV4',
                25 => 'Sequoia',
                26 => 'Starlet',
                27 => 'Supra',
                28 => 'Tundra',
                29 => 'Urban Cruiser',
                30 => 'Verso',
                31 => 'Yaris',
                32 => 'Yaris Verso',
              ],
            ],
            16 => [
              'brand' => 'BMW',
              'models' => [
                0 => 'i3',
                1 => 'i8',
                2 => 'M3',
                3 => 'M4',
                4 => 'M5',
                5 => 'M6',
                6 => 'Rad 1',
                7 => 'Rad 1 Cabrio',
                8 => 'Rad 1 Coupé',
                9 => 'Rad 2',
                10 => 'Rad 2 Active Tourer',
                11 => 'Rad 2 Coupé',
                12 => 'Rad 2 Gran Tourer',
                13 => 'Rad 3',
                14 => 'Rad 3 Cabrio',
                15 => 'Rad 3 Compact',
                16 => 'Rad 3 Coupé',
                17 => 'Rad 3 GT',
                18 => 'Rad 3 Touring',
                19 => 'Rad 4',
                20 => 'Rad 4 Cabrio',
                21 => 'Rad 4 Gran Coupé',
                22 => 'Rad 5',
                23 => 'Rad 5 GT',
                24 => 'Rad 5 Touring',
                25 => 'Rad 6',
                26 => 'Rad 6 Cabrio',
                27 => 'Rad 6 Coupé',
                28 => 'Rad 6 Gran Coupé',
                29 => 'Rad 7',
                30 => 'Rad 8 Coupé',
                31 => 'X1',
                32 => 'X3',
                33 => 'X4',
                34 => 'X5',
                35 => 'X6',
                36 => 'Z3',
                37 => 'Z3 Coupé',
                38 => 'Z3 Roadster',
                39 => 'Z4',
                40 => 'Z4 Roadster',
              ],
            ],
            17 => [
              'brand' => 'Volkswagen',
              'models' => [
                0 => 'Amarok',
                1 => 'Beetle',
                2 => 'Bora',
                3 => 'Bora Variant',
                4 => 'Caddy',
                5 => 'Caddy Van',
                6 => 'Life',
                7 => 'California',
                8 => 'Caravelle',
                9 => 'CC',
                10 => 'Crafter',
                11 => 'Crafter Van',
                12 => 'Crafter Kombi',
                13 => 'CrossTouran',
                14 => 'Eos',
                15 => 'Fox',
                16 => 'Golf',
                17 => 'Golf Cabrio',
                18 => 'Golf Plus',
                19 => 'Golf Sportvan',
                20 => 'Golf Variant',
                21 => 'Jetta',
                22 => 'LT',
                23 => 'Lupo',
                24 => 'Multivan',
                25 => 'New Beetle',
                26 => 'New Beetle Cabrio',
                27 => 'Passat',
                28 => 'Passat Alltrack',
                29 => 'Passat CC',
                30 => 'Passat Variant',
                31 => 'Passat Variant Van',
                32 => 'Phaeton',
                33 => 'Polo',
                34 => 'Polo Van',
                35 => 'Polo Variant',
                36 => 'Scirocco',
                37 => 'Sharan',
                38 => 'T4',
                39 => 'T4 Caravelle',
                40 => 'T4 Multivan',
                41 => 'T5',
                42 => 'T5 Caravelle',
                43 => 'T5 Multivan',
                44 => 'T5 Transporter Shuttle',
                45 => 'Tiguan',
                46 => 'Touareg',
                47 => 'Touran',
              ],
            ],
            18 => [
              'brand' => 'Suzuki',
              'models' => [
                0 => 'Alto',
                1 => 'Baleno',
                2 => 'Baleno kombi',
                3 => 'Grand Vitara',
                4 => 'Grand Vitara XL-7',
                5 => 'Ignis',
                6 => 'Jimny',
                7 => 'Kizashi',
                8 => 'Liana',
                9 => 'Samurai',
                10 => 'Splash',
                11 => 'Swift',
                12 => 'SX4',
                13 => 'SX4 Sedan',
                14 => 'Vitara',
                15 => 'Wagon R+',
              ],
            ],
            19 => [
              'brand' => 'Mercedes-Benz',
              'models' => [
                0 => '100 D',
                1 => '115',
                2 => '124',
                3 => '126',
                4 => '190',
                5 => '190 D',
                6 => '190 E',
                7 => '200 - 300',
                8 => '200 D',
                9 => '200 E',
                10 => '210 Van',
                11 => '210 kombi',
                12 => '310 Van',
                13 => '310 kombi',
                14 => '230 - 300 CE Coupé',
                15 => '260 - 560 SE',
                16 => '260 - 560 SEL',
                17 => '500 - 600 SEC Coupé',
                18 => 'Trieda A',
                19 => 'A',
                20 => 'A L',
                21 => 'AMG GT',
                22 => 'Trieda B',
                23 => 'Trieda C',
                24 => 'C',
                25 => 'C Sportcoupé',
                26 => 'C T',
                27 => 'Citan',
                28 => 'CL',
                29 => 'CL',
                30 => 'CLA',
                31 => 'CLC',
                32 => 'CLK Cabrio',
                33 => 'CLK Coupé',
                34 => 'CLS',
                35 => 'Trieda E',
                36 => 'E',
                37 => 'E Cabrio',
                38 => 'E Coupé',
                39 => 'E T',
                40 => 'Trieda G',
                41 => 'G Cabrio',
                42 => 'GL',
                43 => 'GLA',
                44 => 'GLC',
                45 => 'GLE',
                46 => 'GLK',
                47 => 'Trieda M',
                48 => 'MB 100',
                49 => 'Trieda R',
                50 => 'Trieda S',
                51 => 'S',
                52 => 'S Coupé',
                53 => 'SL',
                54 => 'SLC',
                55 => 'SLK',
                56 => 'SLR',
                57 => 'Sprinter',
              ],
            ],
            20 => [
              'brand' => 'Saab',
              'models' => [
                0 => '9-3',
                1 => '9-3 Cabriolet',
                2 => '9-3 Coupé',
                3 => '9-3 SportCombi',
                4 => '9-5',
                5 => '9-5 SportCombi',
                6 => '900',
                7 => '900 C',
                8 => '900 C Turbo',
                9 => '9000',
              ],
            ],
            21 => [
              'brand' => 'Audi',
              'models' => [
                0 => '100',
                1 => '100 Avant',
                2 => '80',
                3 => '80 Avant',
                4 => '80 Cabrio',
                5 => '90',
                6 => 'A1',
                7 => 'A2',
                8 => 'A3',
                9 => 'A3 Cabriolet',
                10 => 'A3 Limuzina',
                11 => 'A3 Sportback',
                12 => 'A4',
                13 => 'A4 Allroad',
                14 => 'A4 Avant',
                15 => 'A4 Cabriolet',
                16 => 'A5',
                17 => 'A5 Cabriolet',
                18 => 'A5 Sportback',
                19 => 'A6',
                20 => 'A6 Allroad',
                21 => 'A6 Avant',
                22 => 'A7',
                23 => 'A8',
                24 => 'A8 Long',
                25 => 'Q3',
                26 => 'Q5',
                27 => 'Q7',
                28 => 'R8',
                29 => 'RS4 Cabriolet',
                30 => 'RS4/RS4 Avant',
                31 => 'RS5',
                32 => 'RS6 Avant',
                33 => 'RS7',
                34 => 'S3/S3 Sportback',
                35 => 'S4 Cabriolet',
                36 => 'S4/S4 Avant',
                37 => 'S5/S5 Cabriolet',
                38 => 'S6/RS6',
                39 => 'S7',
                40 => 'S8',
                41 => 'SQ5',
                42 => 'TT Coupé',
                43 => 'TT Roadster',
                44 => 'TTS',
              ],
            ],
            22 => [
              'brand' => 'Kia',
              'models' => [
                0 => 'Avella',
                1 => 'Besta',
                2 => 'Carens',
                3 => 'Carnival',
                4 => 'Cee`d',
                5 => 'Cee`d SW',
                6 => 'Cerato',
                7 => 'K 2500',
                8 => 'Magentis',
                9 => 'Opirus',
                10 => 'Optima',
                11 => 'Picanto',
                12 => 'Pregio',
                13 => 'Pride',
                14 => 'Pro Cee`d',
                15 => 'Rio',
                16 => 'Rio Combi',
                17 => 'Rio sedan',
                18 => 'Sephia',
                19 => 'Shuma',
                20 => 'Sorento',
                21 => 'Soul',
                22 => 'Sportage',
                23 => 'Venga',
              ],
            ],
            23 => [
              'brand' => 'Land Rover',
              'models' => [
                0 => '109',
                1 => 'Defender',
                2 => 'Discovery',
                3 => 'Discovery Sport',
                4 => 'Freelander',
                5 => 'Range Rover',
                6 => 'Range Rover Evoque',
                7 => 'Range Rover Sport',
              ],
            ],
            24 => [
              'brand' => 'Dodge',
              'models' => [
                0 => 'Avenger',
                1 => 'Caliber',
                2 => 'Challenger',
                3 => 'Charger',
                4 => 'Grand Caravan',
                5 => 'Journey',
                6 => 'Magnum',
                7 => 'Nitro',
                8 => 'RAM',
                9 => 'Stealth',
                10 => 'Viper',
              ],
            ],
            25 => [
              'brand' => 'Chrysler',
              'models' => [
                0 => '300 C',
                1 => '300 C Touring',
                2 => '300 M',
                3 => 'Crossfire',
                4 => 'Grand Voyager',
                5 => 'LHS',
                6 => 'Neon',
                7 => 'Pacifica',
                8 => 'Plymouth',
                9 => 'PT Cruiser',
                10 => 'Sebring',
                11 => 'Sebring Convertible',
                12 => 'Stratus',
                13 => 'Stratus Cabrio',
                14 => 'Town & Country',
                15 => 'Voyager',
              ],
            ],
            26 => [
              'brand' => 'Ford',
              'models' => [
                0 => 'Aerostar',
                1 => 'B-Max',
                2 => 'C-Max',
                3 => 'Cortina',
                4 => 'Cougar',
                5 => 'Edge',
                6 => 'Escort',
                7 => 'Escort Cabrio',
                8 => 'Escort kombi',
                9 => 'Explorer',
                10 => 'F-150',
                11 => 'F-250',
                12 => 'Fiesta',
                13 => 'Focus',
                14 => 'Focus C-Max',
                15 => 'Focus CC',
                16 => 'Focus kombi',
                17 => 'Fusion',
                18 => 'Galaxy',
                19 => 'Grand C-Max',
                20 => 'Ka',
                21 => 'Kuga',
                22 => 'Maverick',
                23 => 'Mondeo',
                24 => 'Mondeo Combi',
                25 => 'Mustang',
                26 => 'Orion',
                27 => 'Puma',
                28 => 'Ranger',
                29 => 'S-Max',
                30 => 'Sierra',
                31 => 'Street Ka',
                32 => 'Tourneo Connect',
                33 => 'Tourneo Custom',
                34 => 'Transit',
                35 => 'Transit',
                36 => 'Transit Bus',
                37 => 'Transit Connect LWB',
                38 => 'Transit Courier',
                39 => 'Transit Custom',
                40 => 'Transit kombi',
                41 => 'Transit Tourneo',
                42 => 'Transit Valnik',
                43 => 'Transit Van',
                44 => 'Transit Van 350',
                45 => 'Windstar',
              ],
            ],
            27 => [
              'brand' => 'Hummer',
              'models' => [
                0 => 'H2',
                1 => 'H3',
              ],
            ],
            28 => [
              'brand' => 'Hyundai',
              'models' => [
                0 => 'Accent',
                1 => 'Atos',
                2 => 'Atos Prime',
                3 => 'Coupé',
                4 => 'Elantra',
                5 => 'Galloper',
                6 => 'Genesis',
                7 => 'Getz',
                8 => 'Grandeur',
                9 => 'H 350',
                10 => 'H1',
                11 => 'H1 Bus',
                12 => 'H1 Van',
                13 => 'H200',
                14 => 'i10',
                15 => 'i20',
                16 => 'i30',
                17 => 'i30 CW',
                18 => 'i40',
                19 => 'i40 CW',
                20 => 'ix20',
                21 => 'ix35',
                22 => 'ix55',
                23 => 'Lantra',
                24 => 'Matrix',
                25 => 'Santa Fe',
                26 => 'Sonata',
                27 => 'Terracan',
                28 => 'Trajet',
                29 => 'Tucson',
                30 => 'Veloster',
              ],
            ],
            29 => [
              'brand' => 'Infiniti',
              'models' => [
                0 => 'EX',
                1 => 'FX',
                2 => 'G',
                3 => 'G Coupé',
                4 => 'M',
                5 => 'Q',
                6 => 'QX',
              ],
            ],
            30 => [
              'brand' => 'Jaguar',
              'models' => [
                0 => 'Daimler',
                1 => 'F-Pace',
                2 => 'F-Type',
                3 => 'S-Type',
                4 => 'Sovereign',
                5 => 'X-Type',
                6 => 'X-type Estate',
                7 => 'XE',
                8 => 'XF',
                9 => 'XJ',
                10 => 'XJ12',
                11 => 'XJ6',
                12 => 'XJ8',
                13 => 'XJ8',
                14 => 'XJR',
                15 => 'XK',
                16 => 'XK8 Convertible',
                17 => 'XKR',
                18 => 'XKR Convertible',
              ],
            ],
            31 => [
              'brand' => 'Jeep',
              'models' => [
                0 => 'Cherokee',
                1 => 'Commander',
                2 => 'Compass',
                3 => 'Grand Cherokee',
                4 => 'Patriot',
                5 => 'Renegade',
                6 => 'Wrangler',
              ],
            ],
            32 => [
              'brand' => 'Nissan',
              'models' => [
                0 => '100 NX',
                1 => '200 SX',
                2 => '350 Z',
                3 => '350 Z Roadster',
                4 => '370 Z',
                5 => 'Almera',
                6 => 'Almera Tino',
                7 => 'Cabstar E - T',
                8 => 'Cabstar TL2 Valnik',
                9 => 'e-NV200',
                10 => 'GT-R',
                11 => 'Insterstar',
                12 => 'Juke',
                13 => 'King Cab',
                14 => 'Leaf',
                15 => 'Maxima',
                16 => 'Maxima QX',
                17 => 'Micra',
                18 => 'Murano',
                19 => 'Navara',
                20 => 'Note',
                21 => 'NP300 Pickup',
                22 => 'NV200',
                23 => 'NV400',
                24 => 'Pathfinder',
                25 => 'Patrol',
                26 => 'Patrol GR',
                27 => 'Pickup',
                28 => 'Pixo',
                29 => 'Primastar',
                30 => 'Primastar Combi',
                31 => 'Primera',
                32 => 'Primera Combi',
                33 => 'Pulsar',
                34 => 'Qashqai',
                35 => 'Serena',
                36 => 'Sunny',
                37 => 'Terrano',
                38 => 'Tiida',
                39 => 'Trade',
                40 => 'Vanette Cargo',
                41 => 'X-Trail',
              ],
            ],
            33 => [
              'brand' => 'Volvo',
              'models' => [
                0 => '240',
                1 => '340',
                2 => '360',
                3 => '460',
                4 => '850',
                5 => '850 kombi',
                6 => 'C30',
                7 => 'C70',
                8 => 'C70 Cabrio',
                9 => 'C70 Coupé',
                10 => 'S40',
                11 => 'S60',
                12 => 'S70',
                13 => 'S80',
                14 => 'S90',
                15 => 'V40',
                16 => 'V50',
                17 => 'V60',
                18 => 'V70',
                19 => 'V90',
                20 => 'XC60',
                21 => 'XC70',
                22 => 'XC90',
              ],
            ],
            34 => [
              'brand' => 'Daewoo',
              'models' => [
                0 => 'Espero',
                1 => 'Kalos',
                2 => 'Lacetti',
                3 => 'Lanos',
                4 => 'Leganza',
                5 => 'Lublin',
                6 => 'Matiz',
                7 => 'Nexia',
                8 => 'Nubira',
                9 => 'Nubira kombi',
                10 => 'Racer',
                11 => 'Tacuma',
                12 => 'Tico',
              ],
            ],
            35 => [
              'brand' => 'Fiat',
              'models' => [
                0 => '1100',
                1 => '126',
                2 => '500',
                3 => '500L',
                4 => '500X',
                5 => '850',
                6 => 'Barchetta',
                7 => 'Brava',
                8 => 'Cinquecento',
                9 => 'Coupé',
                10 => 'Croma',
                11 => 'Doblo',
                12 => 'Doblo Cargo',
                13 => 'Doblo Cargo Combi',
                14 => 'Ducato',
                15 => 'Ducato Van',
                16 => 'Ducato Kombi',
                17 => 'Ducato Podvozok',
                18 => 'Florino',
                19 => 'Florino Combi',
                20 => 'Freemont',
                21 => 'Grande Punto',
                22 => 'Idea',
                23 => 'Linea',
                24 => 'Marea',
                25 => 'Marea Weekend',
                26 => 'Multipla',
                27 => 'Palio Weekend',
                28 => 'Panda',
                29 => 'Panda Van',
                30 => 'Punto',
                31 => 'Punto Cabriolet',
                32 => 'Punto Evo',
                33 => 'Punto Van',
                34 => 'Qubo',
                35 => 'Scudo',
                36 => 'Scudo Van',
                37 => 'Scudo Kombi',
                38 => 'Sedici',
                39 => 'Seicento',
                40 => 'Stilo',
                41 => 'Stilo Multiwagon',
                42 => 'Strada',
                43 => 'Talento',
                44 => 'Tipo',
                45 => 'Ulysse',
                46 => 'Uno',
                47 => 'X1/9',
              ],
            ],
            36 => [
              'brand' => 'MINI',
              'models' => [
                0 => 'Cooper',
                1 => 'Cooper Cabrio',
                2 => 'Cooper Clubman',
                3 => 'Cooper D',
                4 => 'Cooper D Clubman',
                5 => 'Cooper S',
                6 => 'Cooper S Cabrio',
                7 => 'Cooper S Clubman',
                8 => 'Countryman',
                9 => 'Mini One',
                10 => 'One D',
              ],
            ],
            37 => [
              'brand' => 'Rover',
              'models' => [
                0 => '200',
                1 => '214',
                2 => '218',
                3 => '25',
                4 => '400',
                5 => '414',
                6 => '416',
                7 => '620',
                8 => '75',
              ],
            ],
            38 => [
              'brand' => 'Smart',
              'models' => [
                0 => 'Cabrio',
                1 => 'City-Coupé',
                2 => 'Compact Pulse',
                3 => 'Forfour',
                4 => 'Fortwo cabrio',
                5 => 'Fortwo coupé',
                6 => 'Roadster',
              ],
            ],
          ];
    }

}
