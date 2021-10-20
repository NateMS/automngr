<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CarModelController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/', [ContractController::class, 'dashboard'])->name('dashboard');

    Route::get('reports', [ReportController::class, 'index'])->name('reports');
    Route::get('reports/print', [ReportController::class, 'print'])->name('reports.print');

    Route::prefix('contacts')->group(function () {
        Route::get('/', [ContactController::class, 'index'])->name('contacts');
        Route::get('/print', [ContactController::class, 'print'])->name('contacts.print');
        Route::get('buyers', [ContactController::class, 'buyers'])->name('contacts.buyers');
        Route::get('buyers/print', [ContactController::class, 'buyersPrint'])->name('contacts.buyers.print');
        Route::get('sellers', [ContactController::class, 'sellers'])->name('contacts.sellers');
        Route::get('sellers/print', [ContactController::class, 'sellersPrint'])->name('contacts.sellers.print');
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
        Route::get('/print', [CarController::class, 'print'])->name('cars.print');
        Route::get('unsold', [CarController::class, 'unsold'])->name('cars.unsold');
        Route::get('unsold/print', [CarController::class, 'unsoldPrint'])->name('cars.unsold.print');
        Route::get('sold', [CarController::class, 'sold'])->name('cars.sold');
        Route::get('sold/print', [CarController::class, 'soldPrint'])->name('cars.sold.print');
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
        Route::get('create', [ContractController::class, 'create'])->name('contracts.create');

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
                Route::get('{payment}/print', [PaymentController::class, 'print'])->name('payments.print');
            });

        });
    });

    Route::prefix('documents')->group(function () { 
        Route::get('{document}', [DocumentController::class, 'show'])->name('documents.show');
        Route::delete('delete', [DocumentController::class, 'destroy'])->name('documents.destroy');
        Route::post('upload', [DocumentController::class, 'store'])->name('documents.store');
    });

    Route::post('brands', [BrandController::class, 'store'])->name('brands.store');
    Route::post('models', [CarModelController::class, 'store'])->name('models.store');
});