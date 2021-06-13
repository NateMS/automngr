<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarModelController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::prefix('contacts')->group(function () {
        Route::get('/', [ContactController::class, 'index'])->name('contacts');
        Route::get('buyers', [ContactController::class, 'buyers'])->name('contacts.buyers');
        Route::get('sellers', [ContactController::class, 'sellers'])->name('contacts.sellers');
        Route::get('create', [ContactController::class, 'create'])->name('contacts.create');
        Route::post('/', [ContactController::class, 'store'])->name('contacts.store');
        Route::post('store_for_contract', [ContactController::class, 'storeForContract'])->name('contacts.store_for_contract');

        Route::prefix('{contact}')->group(function () {
            Route::get('/', [ContactController::class, 'show'])->name('contacts.show');
            Route::get('edit', [ContactController::class, 'edit'])->name('contacts.edit');
            Route::put('/', [ContactController::class, 'update'])->name('contacts.update');
            Route::get('delete', [ContactController::class, 'destroy'])->name('contacts.destroy');
            Route::get('restore', [ContactController::class, 'restore'])->name('contacts.restore');
        });
    });

    Route::prefix('cars')->group(function () {
        Route::get('/', [CarController::class, 'index'])->name('cars');
        Route::get('unsold', [CarController::class, 'unsold'])->name('cars.unsold');
        Route::get('sold', [CarController::class, 'sold'])->name('cars.sold');
        Route::get('create', [CarController::class, 'create'])->name('cars.create');
        Route::post('/', [CarController::class, 'store'])->name('cars.store');
        Route::post('/store_for_contract', [CarController::class, 'storeForContract'])->name('cars.store_for_contract');

        Route::prefix('{car}')->group(function () {
            Route::get('/', [CarController::class, 'show'])->name('cars.show');
            Route::get('edit', [CarController::class, 'edit'])->name('cars.edit');
            Route::put('/', [CarController::class, 'update'])->name('cars.update');
            Route::get('delete', [CarController::class, 'destroy'])->name('cars.destroy');
            Route::get('restore', [CarController::class, 'restore'])->name('cars.restore');
        });
    });

    Route::prefix('contracts')->group(function () {
        Route::post('/', [ContractController::class, 'store'])->name('contracts.store');

        Route::prefix('create/{0|1}/')->group(function () {
            Route::get('car/{car}/contact/{contact}', [ContractController::class, 'create'])->name('contracts.create');
            Route::get('car/{car}', [ContractController::class, 'createFromCar'])->name('contracts.create_from_car');
            Route::get('contact/{contact}', [ContractController::class, 'createFromContact'])->name('contracts.create_from_contact');
        });

        Route::prefix('{contract}')->group(function () {
            Route::get('/', [ContractController::class, 'show'])->name('contracts.show');
            Route::get('edit', [ContractController::class, 'edit'])->name('contracts.edit');
            Route::put('/', [ContractController::class, 'update'])->name('contracts.update');
            Route::get('delete', [ContractController::class, 'destroy'])->name('contracts.destroy');
            Route::get('restore', [ContractController::class, 'restore'])->name('contracts.restore');
            Route::get('print', [ContractController::class, 'print'])->name('contracts.print');

            Route::prefix('payments')->group(function () { 
                Route::get('create', [PaymentController::class, 'create'])->name('payments.create');
                Route::delete('delete', [PaymentController::class, 'destroy'])->name('payments.destroy');
                Route::post('/', [PaymentController::class, 'store'])->name('payments.store');
            });

            Route::prefix('documents')->group(function () { 
                Route::get('{document}', [DocumentController::class, 'show'])->name('documents.show');
                Route::delete('delete', [DocumentController::class, 'destroy'])->name('documents.destroy');
                Route::post('/', [DocumentController::class, 'store'])->name('documents.store');
            });
        });
        
    });

    Route::post('brands', [BrandController::class, 'store'])->name('brands.store');
    Route::post('models', [CarModelController::class, 'store'])->name('models.store');
});