<?php

namespace App\Http\Controllers;

use App\Models\CarModel;
use Illuminate\Http\Request;

class CarModelController extends Controller
{
    public function store(Request $request)
    {
        $model = CarModel::create(
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'brand_id' => ['required', 'exists:App\Models\Brand,id'],
            ])
        )->only('id', 'name');

        return $model;
    }
}
