<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Facades\Sessions;
use League\Glide\Server;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Server::class, function ($app) {
            return Server::create([
                'source' => Storage::getDriver(),
                'cache' => Storage::getDriver(),
                'cache_folder' => '.glide-cache',
                'base_url' => 'img',
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Inertia::share([
            'errors' => function(){
                return Session::get('errors')
                ? Session::get('errors')->getBag('default')->getMessage()
                :(object)[];
            },
        ]);

        Inertia::share('flash',function(){
            return [
                'message' => Session::get('message'),
            ];
        });
    }
}
