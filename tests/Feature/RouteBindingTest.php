<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Facade;
use Tests\TestCase;

class RouteBindingTest extends TestCase
{
    /**
     * With `php artisan route:cache` (production) the routes() callback of the
     * RouteServiceProvider is never executed, so route model bindings must not
     * be registered inside it.
     */
    public function test_model_bindings_are_registered_without_loading_routes()
    {
        $this->app->forgetInstance('router');
        Facade::clearResolvedInstance('router');

        (new RouteServiceProvider($this->app))->boot();

        foreach (['car', 'contact', 'contract', 'payment'] as $name) {
            $this->assertNotNull($this->app['router']->getBindingCallback($name), "No binding for [{$name}]");
        }
    }
}
