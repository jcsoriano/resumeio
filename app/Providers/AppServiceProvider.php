<?php

namespace App\Providers;

use App\Magis\MagisProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootMagisSocialite();
    }

    private function bootMagisSocialite()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'magis',
            function ($app) use ($socialite) {
                $config = $app['config']['services.magis'];
                return $socialite->buildProvider(MagisProvider::class, $config);
            }
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
