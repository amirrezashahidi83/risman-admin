<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentColor;

class ChangeTheme
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $panel = Filament::getCurrentPanel();
        if(auth()->check()){
            if(auth()->user()->hasRole('school')){
                FilamentColor::register([
                    'primary' => auth()->user()->school->theme
                ]);
            }
        }
        return $next($request);
    }
}
