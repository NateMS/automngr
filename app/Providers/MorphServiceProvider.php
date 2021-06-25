<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class MorphServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap services.
   *
   * @return void
   */
   public function boot()
   {
      Relation::morphMap([
        'contracts' => 'App\Models\Contract',
        'cars' => 'App\Models\Car',
        'contacts' => 'App\Models\Contact',
      ]);
     }

    /**
     * Register services.
     *
     * @return void
     */
     public function register()
    {
       //
    }
}
