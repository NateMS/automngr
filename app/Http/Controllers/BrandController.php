<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class BrandController extends Controller
{
    public function index()
    {
        return Brand::all()->map(function ($brand) {
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
        });
    }

    public function store(Request $request)
    {
        $brand = Brand::create(
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ])
        )->only('id', 'name');

        return $brand;
    }
}
