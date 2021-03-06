<?php

namespace App\Providers;

use App\Models\Car;
use App\Models\Contact;
use App\Models\Contract;
use App\Models\Payment;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::bind('car', function ($value) {
                if (in_array(Route::currentRouteName(), ['cars.show', 'cars.restore'])) {
                    return Car::withTrashed()->find($value);
                }

                return Car::find($value);
            });

            Route::bind('contact', function ($value) {
                if (in_array(Route::currentRouteName(), ['contacts.show', 'contacts.restore'])) {
                    return Contact::withTrashed()->find($value);
                }

                return Contact::find($value);
            });

            Route::bind('contract', function ($value) {
                if (in_array(Route::currentRouteName(), ['contracts.show', 'contracts.restore', 'payments.destroy', 'payments.print'])) {
                    return Contract::withTrashed()->find($value);
                }

                return Contract::find($value);
            });

            Route::bind('payment', function ($value) {
                return \App\Models\Payment::find($value);
            });

            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
