<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $direction = $this->getDirection($request);
        $sortBy = $this->getSortBy($request);
        $cars = $this->getWithCustomSort($sortBy, $direction);

        return Inertia::render('Cars/Index', [
            'filters' => $request->all('search', 'trashed'),
            'sort' => [
                'by' => $sortBy,
                'direction' => $direction,
            ],
            'cars' => $cars->filter($request->only('search', 'trashed'))
                ->orderByInitialDate()
                ->paginate(50)
                ->withQueryString()
                ->through(function ($car) {
                    return [
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
                    ];
                }),
        ]);
    }

    private function getWithCustomSort(string $sortBy, string $direction)
    {
        switch($sortBy) {
            case 'initial_date':
                return Car::orderBy('initial_date', $direction);
            case 'stammnummer':
                return Car::orderBy('stammnummer', $direction);
            default:
                //return Car::orderByName($direction);
                return Car::orderBy('initial_date', $direction);
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
        return Inertia::render('Cars/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function show(Car $car)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function edit(Car $car)
    {
        //
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
        //
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
