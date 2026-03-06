<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use App\Models\Examen;
use App\Observers\ExamenObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $key = strtolower($request->input('email')) . '|' . $request->ip();
            return Limit::perMinute(5)->by($key)->response(function () {
                return back()->with('error', 'Demasiados intentos. Espera un minuto antes de intentar nuevamente.')->withInput();
            });
        });

        RateLimiter::for('password-reset', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(120)->by($request->user()?->id ?: $request->ip());
        });

        // Observers
        Examen::observe(ExamenObserver::class);

        // Events & Listeners
        Event::listen(
            \App\Events\ExamenPublicado::class,
            \App\Listeners\NotificarExamenPublicado::class,
        );

        Event::listen(
            \App\Events\IntentoFinalizado::class,
            \App\Listeners\NotificarIntentoFinalizado::class,
        );

        Event::listen(
            \App\Events\EstudianteMatriculado::class,
            \App\Listeners\NotificarMatricula::class,
        );
    }
}
