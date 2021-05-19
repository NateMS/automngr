<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Inertia\Inertia;
use App\Models\Brand;
use App\Enums\InsuranceType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;

class CarController extends Controller
{
    public function index(Request $request)
    {
        return $this->renderCarsList($request, Car::query(), 'Cars/Index');
    }

    public function unsold(Request $request)
    {
        return $this->renderCarsList($request, Car::unsoldCars(), 'Cars/Unsold');
    }

    public function sold(Request $request)
    {
        return $this->renderCarsList($request, Car::soldCars(), 'Cars/Sold');
    }

    private function renderCarsList(Request $request, $cars, string $renderPage) {
        $direction = $this->getDirection($request);
        $sortBy = $this->getSortBy($request);
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
                    'buy_price' => $car->latestSellerContract() ? $car->latestSellerContract()->price : '',
                    // 'buy_price' => $car->buy_price->format(),
                    // 'seller' => $car->seller->only('name'),
                    // 'buyer' => $car->buyer->only('name'),
                    'car_model' => $car->carModel->only('name'),
                    'name' => $car->name,
                    'initial_date' => $car->initial_date,
                    'deleted_at' => $car->deleted_at,
                    'link' => route('cars.edit', $car),
                ]),
        ]);
    }

    private function getWithCustomSort($cars, string $sortBy, string $direction)
    {
        switch($sortBy) {
            case 'initial_date':
                return $cars->orderBy('initial_date', $direction);
            case 'stammnummer':
                return $cars->orderBy('stammnummer', $direction);
            default:
                //return $cars->orderByName($direction);
                return $cars->orderBy('initial_date', $direction);
        }
    }

    private function getSortBy(Request $request)
    {
        if ($request->has('sortby')) {
            return $request->get('sortby');
        }

        return 'name';
    }

    private function getDirection(Request $request)
    {
        if ($request->has('direction')) {
            if (in_array($request->get('direction'), ['asc', 'desc'])) {
                return $request->get('direction');
            }
        }

        return 'asc';
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $car = Car::create(
            $request->validate([
                'stammnummer' => ['required', 'unique:cars', 'string', 'size:11', 'regex:/[0-9]{3}[.][0-9]{3}[.][0-9]{3}/i'],
                'vin' => ['required', 'unique:cars', 'string', 'size:17'],
                'initial_date' => ['required', 'date'],
                'last_check_date' => ['required', 'date'],
                'colour' => ['nullable', 'max:75'],
                'car_model_id' => ['required', 'exists:App\Models\CarModel,id'],
                'kilometers' => ['required', 'max:75'],
            ])
        );

        return Redirect::route('cars.edit', $car);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
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
                // 'buy_contracts' => $car->buyContracts()
                //     // ->with('contact')
                //     ->through(fn ($contract) => [
                //         'date' => $contract->date,
                //         'price' => $contract->price,
                //         'buyer' => 'aaa', // $contract->contact->name,
                //         'link' => route('cars.edit', $car),
                //     ]),
                // 'sell_contracts' => $car->sellContracts()
                //     // ->with('contact')
                //     ->through(fn ($contract) => [
                //         'date' => $contract->date,
                //         'price' => $contract->price,
                //         'seller' => 'bbb', // $contract->seller->name,
                //         'link' => route('cars.edit', $car),
                //         'insurance_type' => InsuranceType::fromValue((int)$contract->insurance_type)->key,
                //     ]),
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
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
            ])
        );

        return Redirect::back()->with('success', 'Auto geändert.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(Car $car)
    {
        $car->delete();
        return Redirect::back()->with('success', 'Auto gelöscht.');
    }

    public function restore(Car $car)
    {
        $car->restore();

        return Redirect::back()->with('success', 'Auto wiederhergestellt.');
    }
}
