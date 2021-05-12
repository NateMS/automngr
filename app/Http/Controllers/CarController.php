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
    public function index()
    {
        return Inertia::render('Cars/Index', [
            'filters' => request()->all('search', 'trashed'),
            'cars' => Car::all()
                ->filter(request()->only('search', 'trashed'))
                ->paginate()
                ->withQueryString()
                ->through(function ($car) {
                    return [
                        'id' => $car->id,
                        'stammnummer' => $car->stammnummer,
                        'vin' => $car->vin,
                        'bought_at' => $car->bought_at,
                        'buy_price' => $car->buy_price,
                        'seller' => $car->seller->only('name'),
                        'buyer' => $car->buyer->only('name'),
                        'car_model' => $car->carModel->only('name'),
                        'name' => $car->name,
                        'phone' => $car->phone,
                        'zipcode' => $car->city,
                        'city' => $car->city,
                        'deleted_at' => $car->deleted_at,
                    ];
                }),
        ]);
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
