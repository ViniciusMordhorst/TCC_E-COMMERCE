<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || $user->tipo !== 1) {
            return redirect()->route('home')->with('error', 'Acesso negado!');
        }

        return $next($request);
    }
}
