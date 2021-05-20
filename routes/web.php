<?php

use Illuminate\Foundation\Application;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarModelController;
use App\Http\Controllers\ContractController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Dashboard', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('contacts', [ContactController::class, 'index'])
    ->name('contacts')
    ->middleware(['auth:sanctum', 'verified']);

Route::get('contacts/buyers', [ContactController::class, 'buyers'])
    ->name('contacts.buyers')
    ->middleware(['auth:sanctum', 'verified']);

Route::get('contacts/sellers', [ContactController::class, 'sellers'])
    ->name('contacts.sellers')
    ->middleware(['auth:sanctum', 'verified']);

Route::get('contacts/create', [ContactController::class, 'create'])
    ->name('contacts.create')
    ->middleware(['auth:sanctum', 'verified']);

Route::post('contacts', [ContactController::class, 'store'])
    ->name('contacts.store')
    ->middleware(['auth:sanctum', 'verified']);

Route::get('contacts/{contact}', [ContactController::class, 'edit'])
    ->name('contacts.edit')
    ->middleware(['auth:sanctum', 'verified']);

Route::put('contacts/{contact}', [ContactController::class, 'update'])
    ->name('contacts.update')
    ->middleware(['auth:sanctum', 'verified']);

Route::delete('contacts/{contact}', [ContactController::class, 'destroy'])
    ->name('contacts.destroy')
    ->middleware(['auth:sanctum', 'verified']);

Route::put('contacts/{contact}/restore', [ContactController::class, 'restore'])
    ->name('contacts.restore')
    ->middleware(['auth:sanctum', 'verified']);

Route::get('cars', [CarController::class, 'index'])
    ->name('cars')
    ->middleware(['auth:sanctum', 'verified']);

Route::get('cars/unsold', [CarController::class, 'unsold'])
    ->name('cars.unsold')
    ->middleware(['auth:sanctum', 'verified']);

Route::get('cars/sold', [CarController::class, 'sold'])
    ->name('cars.sold')
    ->middleware(['auth:sanctum', 'verified']);

Route::get('cars/create', [CarController::class, 'create'])
    ->name('cars.create')
    ->middleware(['auth:sanctum', 'verified']);

Route::get('cars/{car}/edit', [CarController::class, 'edit'])
    ->name('cars.edit')
    ->middleware(['auth:sanctum', 'verified']);

    Route::get('cars/{car}', [CarController::class, 'show'])
    ->name('cars.show')
    ->middleware(['auth:sanctum', 'verified']);

Route::put('cars/{car}', [CarController::class, 'update'])
    ->name('cars.update')
    ->middleware(['auth:sanctum', 'verified']);

Route::post('cars', [CarController::class, 'store'])
    ->name('cars.store')
    ->middleware(['auth:sanctum', 'verified']);

Route::post('brands', [BrandController::class, 'store'])
    ->name('brands.store')
    ->middleware(['auth:sanctum', 'verified']);

Route::post('models', [CarModelController::class, 'store'])
    ->name('models.store')
    ->middleware(['auth:sanctum', 'verified']);

Route::get('contracts', [ContractController::class, 'index'])
    ->name('contracts')
    ->middleware(['auth:sanctum', 'verified']);
 
Route::get('contracts/ankaufvertraege', [ContractController::class, 'buyContracts'])
    ->name('contracts.buy_contracts')
    ->middleware(['auth:sanctum', 'verified']);

Route::get('contracts/kaufvertraege', [ContractController::class, 'sellContracts'])
    ->name('contracts.sell_contracts')
    ->middleware(['auth:sanctum', 'verified']);