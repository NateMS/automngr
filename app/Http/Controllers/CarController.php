<?php

namespace App\Http\Controllers;

use App\Exports\Export;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Contract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class CarController extends Controller
{
    public function index(Request $request)
    {
        return $this->renderCarsList($request, Car::withContractCount(), 'Cars/Index');
    }

    public function unsold(Request $request)
    {
        return $this->renderCarsList($request, Car::unsoldOnly(), 'Cars/Unsold');
    }

    public function sold(Request $request)
    {
        return $this->renderCarsList($request, Car::soldOnly(), 'Cars/Sold', 'sell_contract.date');
    }

    public function print(Request $request)
    {
        $headings = [
            'Marke',
            'Modell',
            'Stammnummer',
            'Chassisnummer',
            'Farbe',
            'Inverkehrssetzung',
            'Letzte Überprüfung',
            'Kilometerstand',
            'Bemerkungen',
            'Bekannter Schaden',
            'Verkäufer',
            'Einkaufsdatum',
            'Einkaufspreis',
            'Käufer',
            'Verkaufsdatum',
            'Verkaufspreis',
        ];

        $direction = $this->getDirection($request, 'desc');
        $sortBy = $this->getSortBy($request, 'buy_contract.date');
        $cars = $this->getWithCustomSort(Car::query(), $sortBy, $direction)
            ->filter($request->only('search', 'trashed'))
            ->get()
            ->map(function ($car) {
                $bcontract = $car->latestBuyContract();
                $scontract = $car->latestSellContract();

                return [
                    'brand' => $car->brand->name,
                    'model' => $car->carModel->name,
                    'stammnummer' => $car->stammnummer,
                    'vin' => $car->vin,
                    'colour' => $car->colour,
                    'initial_date' => $car->initial_date_formatted,
                    'last_check_date' => $car->last_check_date_formatted,
                    'kilometers' => $car->kilometers_formatted,
                    'notes' => $car->notes,
                    'known_damage' => $car->known_damage,
                    'seller' =>  $bcontract ? $bcontract->contact->full_title : null,
                    'buy_date' =>  $bcontract ? $bcontract->date_formatted : null,
                    'price' => $bcontract ? $bcontract->price_for_excel : null,
                    'buyer' =>  $scontract ? $scontract->contact->full_title : null,
                    'sell_date' =>  $scontract ? $scontract->date_formatted : null,
                    'sell_price' => $scontract ? $scontract->price_for_excel : null,
                ];
            });

        return Excel::download(new Export($cars, $headings), date('Y-m-d').'-Alle-Autos.xlsx');
    }

    public function unsoldPrint(Request $request)
    {
        $headings = [
            'Marke',
            'Modell',
            'Stammnummer',
            'Chassisnummer',
            'Farbe',
            'Inverkehrssetzung',
            'Letzte Überprüfung',
            'Kilometerstand',
            'Bemerkungen',
            'Bekannter Schaden',
            'Verkäufer',
            'Einkaufsdatum',
            'Einkaufspreis',
        ];

        $direction = $this->getDirection($request, 'desc');
        $sortBy = $this->getSortBy($request, 'buy_contract.date');
        $cars = $this->getWithCustomSort(Car::unsoldOnly(), $sortBy, $direction)
            ->filter($request->only('search', 'trashed'))
            ->get()
            ->map(function ($car) {
                $contract = $car->latestBuyContract();

                return [
                    'brand' => $car->brand->name,
                    'model' => $car->carModel->name,
                    'stammnummer' => $car->stammnummer,
                    'vin' => $car->vin,
                    'colour' => $car->colour,
                    'initial_date' => $car->initial_date_formatted,
                    'last_check_date' => $car->last_check_date_formatted,
                    'kilometers' => $car->kilometers_formatted,
                    'notes' => $car->notes,
                    'known_damage' => $car->known_damage,
                    'seller' =>  $contract ? $contract->contact->full_title : null,
                    'buy_date' =>  $contract ? $contract->date_formatted : null,
                    'price' => $contract ? $contract->price_for_excel : null,
                ];
            });

        return Excel::download(new Export($cars, $headings), date('Y-m-d').'-Meine-Autos.xlsx');
    }

    public function soldPrint(Request $request)
    {
        $headings = [
            'Marke',
            'Modell',
            'Stammnummer',
            'Chassisnummer',
            'Farbe',
            'Inverkehrssetzung',
            'Letzte Überprüfung',
            'Kilometerstand',
            'Bemerkungen',
            'Bekannter Schaden',
            'Verkäufer',
            'Einkaufsdatum',
            'Einkaufspreis',
            'Käufer',
            'Verkaufsdatum',
            'Verkaufspreis',
        ];

        $direction = $this->getDirection($request, 'desc');
        $sortBy = $this->getSortBy($request, 'buy_contract.date');
        $cars = $this->getWithCustomSort(Car::soldOnly(), $sortBy, $direction)
            ->filter($request->only('search', 'trashed'))
            ->get()
            ->map(function ($car) {
                $bcontract = $car->latestBuyContract();
                $scontract = $car->latestSellContract();

                return [
                    'brand' => $car->brand->name,
                    'model' => $car->carModel->name,
                    'stammnummer' => $car->stammnummer,
                    'vin' => $car->vin,
                    'colour' => $car->colour,
                    'initial_date' => $car->initial_date_formatted,
                    'last_check_date' => $car->last_check_date_formatted,
                    'kilometers' => $car->kilometers_formatted,
                    'notes' => $car->notes,
                    'known_damage' => $car->known_damage,
                    'seller' =>  $bcontract ? $bcontract->contact->full_title : null,
                    'buy_date' =>  $bcontract ? $bcontract->date_formatted : null,
                    'price' => $bcontract ? $bcontract->price_for_excel : null,
                    'buyer' =>  $scontract ? $scontract->contact->full_title : null,
                    'sell_date' =>  $scontract ? $scontract->date_formatted : null,
                    'sell_price' => $scontract ? $scontract->price_for_excel : null,
                ];
            });

        return Excel::download(new Export($cars, $headings), date('Y-m-d').'-Verkaufte-Autos.xlsx');
    }

    private function renderCarsList(Request $request, $cars, string $renderPage, string $defaultSort = 'buy_contract.date')
    {
        $direction = $this->getDirection($request, 'desc');
        $sortBy = $this->getSortBy($request, $defaultSort);
        $cars = $this->getWithCustomSort($cars, $sortBy, $direction);

        return Inertia::render($renderPage, [
            'filters' => $request->all('search', 'trashed', 'brand'),
            'sort' => [
                'by' => $sortBy,
                'direction' => $direction,
            ],
            'cars' => $cars->filter($request->only('search', 'trashed', 'brand'))
                ->paginate(50)
                ->withQueryString()
                ->through(fn ($car) => [
                    'id' => $car->id,
                    'stammnummer' => $car->stammnummer,
                    'vin' => $car->vin,
                    'buy_contract' => $this->getContractFields($car->latestBuyContract()),
                    'sell_contract' => $this->getContractFields($car->latestSellContract()),
                    'car_model' => $car->carModel->only('name'),
                    'name' => $car->name_with_year,
                    'deleted_at' => $car->deleted_at,
                    'link' => route('cars.show', $car),
                ]),
        ]);
    }

    private function getContractFields(?Contract $contract)
    {
        if (! $contract) {
            return null;
        }
        $contact = $contract->contact;

        return [
            'id' => $contract->id,
            'date' => $contract->date_formatted,
            'price' => $contract->price->format(),
            'contact' => $contact->full_title,
            'link' => route('contracts.show', $contract),
        ];
    }

    private function getWithCustomSort($cars, string $sortBy, string $direction)
    {
        return match ($sortBy) {
            'name' => $cars
                ->leftJoin('car_models', 'cars.car_model_id', '=', 'car_models.id')
                ->leftJoin('brands', 'car_models.brand_id', '=', 'brands.id')
                ->orderBy('brands.name', $direction)
                ->orderBy('car_models.name', $direction),
            'initial_date' => $cars->orderBy('initial_date', $direction),
            'stammnummer' => $cars->orderBy('stammnummer', $direction),
            'buy_contract.date' => $cars
                ->leftJoin('contracts', function ($join) {
                    $join->on('contracts.car_id', '=', 'cars.id')
                            ->on('contracts.id', '=', DB::raw("(SELECT id from contracts WHERE contracts.car_id = cars.id and type = '0' order by date desc limit 1)"));
                })
                ->orderBy('contracts.date', $direction),
            'buy_contract.price' => $cars
                ->leftJoin('contracts', function ($join) {
                    $join->on('contracts.car_id', '=', 'cars.id')
                        ->on('contracts.id', '=', DB::raw("(SELECT id from contracts WHERE contracts.car_id = cars.id and type = '0' order by date desc limit 1)"));
                })
                ->orderBy('contracts.price', $direction),
            'sell_contract.date' => $cars
                ->leftJoin('contracts', function ($join) {
                    $join->on('contracts.car_id', '=', 'cars.id')
                            ->on('contracts.id', '=', DB::raw("(SELECT id from contracts WHERE contracts.car_id = cars.id and type = '1' order by date desc limit 1)"));
                })
                ->orderBy('contracts.date', $direction),
            'sell_contract.price' => $cars
                ->leftJoin('contracts', function ($join) {
                    $join->on('contracts.car_id', '=', 'cars.id')
                        ->on('contracts.id', '=', DB::raw("(SELECT id from contracts WHERE contracts.car_id = cars.id and type = '1' order by date desc limit 1)"));
                }
            )
                ->orderBy('contracts.price', $direction),
            default => $cars->orderBy('initial_date', $direction),
        };
    }

    private function getSortBy(Request $request, $defaultSort)
    {
        if ($request->has('sortby')) {
            return $request->get('sortby');
        }

        return $defaultSort;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Cars/Create', [
            'brands' => Brand::all()->map(function ($brand) {
                return [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'models' => $brand->carModels()->get()
                    ->map(function ($carModel) {
                        return [
                            'id' => $carModel->id,
                            'name' => $carModel->name,
                        ];
                    }),
                ];
            }),
        ]);
    }

    public function store(Request $request)
    {
        $car = $this->createCar($request);

        session()->flash('flash.banner', 'Auto erstellt.');

        return Redirect::route('cars.show', $car);
    }

    private function createCar($request): Car
    {
        $request->merge([
            'vin' => mb_strtoupper($request->get('vin')),
        ]);

        $request->validate($this->getValidationRules());

        if ($request->get('initial_date')) {
            $request->merge(['initial_date' => Carbon::parse($request->get('initial_date'))->format('Y-m-d')]);
        }

        if ($request->get('last_check_date')) {
            $request->merge(['last_check_date' => Carbon::parse($request->get('last_check_date'))->format('Y-m-d')]);
        }

        return Car::create($request->all());
    }

    public function storeForContract(Request $request)
    {
        $car = $this->createCar($request);

        return response()->json([
            'id' => $car->id,
            'stammnummer' => $car->stammnummer,
            'vin' => $car->vin,
            'name' => $car->name,
            'label' => $car->name.' ('.$car->stammnummer.')',
            'colour' => $car->colour,
            'last_check_date' => $car->last_check_date_formatted,
            'kilometers' => $car->kilometers,
            'initial_date' => $car->initial_date_formatted,
        ]);
    }

    private function getValidationRules()
    {
        return [
            'stammnummer' => ['required', 'unique:cars,stammnummer,NULL,id,deleted_at,NULL', 'string', 'size:11', 'regex:/[0-9]{3}[.][0-9]{3}[.][0-9]{3}/i'],
            'vin' => ['required', 'unique:cars,vin,NULL,id,deleted_at,NULL', 'string', 'size:17'],
            'initial_date' => ['required', 'date_format:"d.m.Y"'],
            'last_check_date' => ['nullable', 'date_format:"d.m.Y"'],
            'colour' => ['nullable', 'max:75'],
            'car_model_id' => ['required', 'exists:App\Models\CarModel,id'],
            'kilometers' => ['required', 'max:75'],
            'known_damage' => ['nullable'],
            'notes' => ['nullable'],
        ];
    }

    public function show(Car $car)
    {
        return Inertia::render('Cars/Show', [
            'car' => [
                'id' => $car->id,
                'stammnummer' => $car->stammnummer,
                'vin' => $car->vin,
                'car_model' => $car->carModel->only('id', 'name'),
                'brand' => $car->brand,
                'name' => $car->name,
                'initial_date' => $car->initial_date_formatted,
                'colour' => $car->colour,
                'last_check_date' => $car->last_check_date_formatted,
                'kilometers' => $car->kilometers_formatted,
                'known_damage' => $car->known_damage,
                'notes' => $car->notes,
                'deleted_at' => $car->deleted_at,
                'documents' => $car->documents()->orderBy('created_at', 'asc')->get()
                    ->map(function ($document) {
                        return [
                            'id' => $document->id,
                            'name' => $document->name,
                            'size' => $document->size,
                            'extension' => $document->extension,
                            'link' => $document->link,
                            'created_at' => $document->created_at,
                        ];
                    }),
                'buy_contracts' => $car->buyContracts()
                    ->orderBy('date', 'desc')
                    ->with('contact')
                    ->get()
                    ->map(function ($contract) {
                        return $this->getContractFields($contract);
                    }),
                'sell_contracts' => $car->sellContracts()
                    ->orderBy('date', 'desc')
                    ->with('contact')
                    ->get()
                    ->map(function ($contract) {
                        return $this->getContractFields($contract);
                    }),
            ],
        ]);
    }

    public function edit(Car $car)
    {
        return Inertia::render('Cars/Edit', [
            'car' => [
                'id' => $car->id,
                'stammnummer' => $car->stammnummer,
                'vin' => $car->vin,
                'car_model' => $car->carModel->only('id', 'name'),
                'brand' => $car->brand,
                'name' => $car->name,
                'initial_date' => $car->initial_date,
                'colour' => $car->colour,
                'last_check_date' => $car->last_check_date,
                'kilometers' => $car->kilometers,
                'known_damage' => $car->known_damage,
                'notes' => $car->notes,
                'deleted_at' => $car->deleted_at,
            ],
            'brands' => Brand::all()->map(function ($brand) {
                return [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'models' => $brand->carModels()->get()
                        ->map(function ($carModel) {
                            return [
                                'id' => $carModel->id,
                                'name' => $carModel->name,
                            ];
                        }),
                ];
            }),
        ]);
    }

    public function update(Request $request, Car $car)
    {
        $this->updateCar($request, $car);
        session()->flash('flash.banner', 'Auto geändert.');

        return Redirect::route('cars.show', $car);
    }

    private function updateCar(Request $request, Car $car): bool
    {
        $request->merge([
            'vin' => mb_strtoupper($request->get('vin')),
        ]);

        $request->validate([
            'stammnummer' => ['required', 'unique:cars,stammnummer,'.$car->id.',id,deleted_at,NULL', 'string', 'size:11', 'regex:/[0-9]{3}[.][0-9]{3}[.][0-9]{3}/i'],
            'vin' => ['required', 'unique:cars,vin,'.$car->id.',id,deleted_at,NULL', 'string', 'size:17'],
            'initial_date' => ['required', 'date_format:"d.m.Y"'],
            'last_check_date' => ['nullable', 'date_format:"d.m.Y"'],
            'colour' => ['nullable', 'max:75'],
            'car_model_id' => ['required', 'exists:App\Models\CarModel,id'],
            'kilometers' => ['required', 'max:75'],
            'known_damage' => ['nullable'],
            'notes' => ['nullable'],
        ]);

        if ($request->get('initial_date')) {
            $request->merge(['initial_date' => Carbon::parse($request->get('initial_date'))->format('Y-m-d')]);
        }

        if ($request->get('last_check_date')) {
            $request->merge(['last_check_date' => Carbon::parse($request->get('last_check_date'))->format('Y-m-d')]);
        }

        return $car->update($request->all());
    }

    public function destroy(Car $car)
    {
        $car->delete();
        session()->flash('flash.banner', 'Auto gelöscht.');

        return Redirect::route('cars.show', $car);
    }

    public function restore(Car $car)
    {
        $car->restore();
        session()->flash('flash.banner', 'Auto wiederhergestellt.');

        return Redirect::route('cars.show', $car);
    }
}
