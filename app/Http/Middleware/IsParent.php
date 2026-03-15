<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsParent
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'parent') {
            return $next($request);
        }

        abort(403, 'Accès réservé aux parents.');
    }
}