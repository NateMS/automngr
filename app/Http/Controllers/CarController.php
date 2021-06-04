<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Contract;
use App\Enums\ContractType;
use App\Enums\InsuranceType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

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

    private function renderCarsList(Request $request, $cars, string $renderPage, string $defaultSort = 'buy_contract.date') {
        $direction = $this->getDirection($request, 'desc');
        $sortBy = $this->getSortBy($request, $defaultSort);
        $cars = $this->getWithCustomSort($cars, $sortBy, $direction);

        return Inertia::render($renderPage, [
            'filters' => $request->all('search', 'trashed'),
            'sort' => [
                'by' => $sortBy,
                'direction' => $direction,
            ],
            'cars' => $cars->filter($request->only('search', 'trashed'))
                ->paginate(50)
                ->withQueryString()
                ->through(fn ($car) => [
                    'id' => $car->id,
                    'stammnummer' => $car->stammnummer,
                    'vin' => $car->vin,
                    'buy_contract' => $this->getContractFields($car->latestBuyContract()),
                    'sell_contract' => $car->isSold() ? $this->getContractFields($car->latestSellContract()) : null,
                    'profit' => $car->latestProfit(),
                    'car_model' => $car->carModel->only('name'),
                    'name' => $car->name,
                    'initial_date' => $car->initial_date_formatted,
                    'deleted_at' => $car->deleted_at,
                    'link' => route('cars.show', $car),
                ]),
        ]);
    }

    private function getContractFields(?Contract $contract) {
        if (!$contract) {
            return null;
        }
        $contact = $contract->contact;
        return [
            'id' => $contract->id,
            'date' => $contract->date_formatted,
            'price' => $contract->price->format(),
            'type' => $contract->type,
            'is_sell_contract' => $contract->isSellContract(),
            'insurance_type' => $contract->insurance_type ? InsuranceType::fromValue((int)$contract->insurance_type)->key : null,
            'contact' => [
                'id' => $contact->id,
                'name' => $contact->name,
                'firstname' => $contact->firstname,
                'lastname' => $contact->lastname,
                'phone' => $contact->phone,
                'address' => $contact->address,
                'zip' => $contact->zip,
                'city' => $contact->city,
                'country' => $contact->country,
                'company' => $contact->company,
                'email' => $contact->email,
                'link' => route('contacts.show', $contact),
            ],
            'link' => route('contracts.show', $contract),
        ];
    }

    private function getWithCustomSort($cars, string $sortBy, string $direction)
    {
        switch($sortBy) {
            case 'name':
                return $cars
                    ->leftJoin('car_models', 'cars.car_model_id', '=', 'car_models.id')
                    ->leftJoin('brands', 'car_models.brand_id', '=', 'brands.id')
                    ->orderBy('brands.name', $direction)
                    ->orderBy('car_models.name', $direction);
            case 'initial_date':
                return $cars->orderBy('initial_date', $direction);
            case 'stammnummer':
                return $cars->orderBy('stammnummer', $direction);
            case 'buy_contract.date':
                return $cars
                    ->leftJoin('contracts', function($join) { 
                        $join->on('contracts.car_id', '=', 'cars.id')
                                ->on('contracts.id', '=', DB::raw("(SELECT id from contracts WHERE contracts.car_id = cars.id and type = '0' order by date desc limit 1)")); 
                        })
                    ->orderBy('contracts.date', $direction);
            case 'buy_contract.price':
                return $cars
                    ->leftJoin('contracts', function($join) { 
                        $join->on('contracts.car_id', '=', 'cars.id')
                            ->on('contracts.id', '=', DB::raw("(SELECT id from contracts WHERE contracts.car_id = cars.id and type = '0' order by date desc limit 1)")); 
                        })
                    ->orderBy('contracts.price', $direction);
            case 'sell_contract.date':
                return $cars
                    ->leftJoin('contracts', function($join) { 
                        $join->on('contracts.car_id', '=', 'cars.id')
                                ->on('contracts.id', '=', DB::raw("(SELECT id from contracts WHERE contracts.car_id = cars.id and type = '1' order by date desc limit 1)")); 
                        })
                    ->orderBy('contracts.date', $direction);
            case 'sell_contract.price':
                return $cars
                    ->leftJoin('contracts', function($join) { 
                        $join->on('contracts.car_id', '=', 'cars.id')
                            ->on('contracts.id', '=', DB::raw("(SELECT id from contracts WHERE contracts.car_id = cars.id and type = '1' order by date desc limit 1)")); 
                        })
                    ->orderBy('contracts.price', $direction);
            case 'profit':
                return $cars
                    ->leftJoin('contracts as buy_contracts', function($join) { 
                        $join->on('buy_contracts.car_id', '=', 'cars.id')
                            ->on('buy_contracts.id', '=', DB::raw("(SELECT id from contracts WHERE contracts.car_id = cars.id and type = '0' order by date desc limit 1)")); 
                        })
                    ->leftJoin('contracts as sell_contracts', function($join) { 
                        $join->on('sell_contracts.car_id', '=', 'cars.id')
                            ->on('sell_contracts.id', '=', DB::raw("(SELECT id from contracts WHERE contracts.car_id = cars.id and type = '1' order by date desc limit 1)")); 
                        })
                    ->orderByRaw('(sell_contracts.price - buy_contracts.price) ' . $direction);
            default:
                return $cars->orderBy('initial_date', $direction);
        }
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
        $car = Car::create(
            $request->validate($this->getValidationRules())
        );

        session()->flash('flash.banner', 'Auto erstellt.');
        return Redirect::route('cars.show', $car);
    }

    public function storeForContract(Request $request)
    {
        $car = Car::create(
            $request->validate($this->getValidationRules())
        );

        return response()->json([
            'id' => $car->id,
            'stammnummer' => $car->stammnummer,
            'vin' => $car->vin,
            'name' => $car->name,
            'colour' => $car->colour,
            'last_check_date' => $car->last_check_date_formatted,
            'kilometers' => $car->kilometers,
            'initial_date' => $car->initial_date_formatted,
        ]);
    }

    private function getValidationRules()
    {
        return [
            'stammnummer' => ['required', 'unique:cars', 'string', 'size:11', 'regex:/[0-9]{3}[.][0-9]{3}[.][0-9]{3}/i'],
            'vin' => ['required', 'unique:cars', 'string', 'size:17'],
            'initial_date' => ['required', 'date'],
            'last_check_date' => ['required', 'date'],
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
                'kilometers' => $car->kilometers,
                'known_damage' => $car->known_damage,
                'notes' => $car->notes,
                'deleted_at' => $car->deleted_at,
                'buy_contracts' => $car->buyContracts()
                    ->orderBy('date', 'asc')
                    ->paginate(50)
                    ->through(fn ($contract) => $this->getContractFields($contract)),
                'sell_contracts' => $car->sellContracts()
                    ->orderBy('date', 'asc')
                    ->paginate(50)
                    ->through(fn ($contract) => $this->getContractFields($contract)),
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
        $car->update(
            $request->validate([
                'stammnummer' => ['required', 'unique:cars,stammnummer,' . $car->id, 'string', 'size:11', 'regex:/[0-9]{3}[.][0-9]{3}[.][0-9]{3}/i'],
                'vin' => ['required', 'unique:cars,vin,' . $car->id, 'string', 'size:17'],
                'initial_date' => ['required', 'date'],
                'last_check_date' => ['required', 'date'],
                'colour' => ['nullable', 'max:75'],
                'car_model_id' => ['required', 'exists:App\Models\CarModel,id'],
                'kilometers' => ['required', 'max:75'],
                'known_damage' => ['nullable'],
                'notes' => ['nullable'],
            ])
        );

        session()->flash('flash.banner', 'Auto geändert.');
        return Redirect::route('cars.show', $car);
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
