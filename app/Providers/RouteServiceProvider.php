<?php

namespace App\Providers;

use App\Models\Car;
use App\Models\Contact;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/dashboard';

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
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
            
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
                if (in_array(Route::currentRouteName(), ['contracts.show', 'contracts.restore'])) {
                    return Contract::withTrashed()->find($value);
                }
                return Contract::find($value);
            });
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
