<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InactividadMiddleware
{
    protected int $tiempoLimite = 30;

    public function handle(Request $request, Closure $next): Response
    {
        $ultimaActividad = session('ultima_actividad');

        if ($ultimaActividad && (time() - $ultimaActividad) > ($this->tiempoLimite * 60)) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Sesión cerrada por inactividad.');
        }

        session(['ultima_actividad' => time()]);

        return $next($request);
    }
}
