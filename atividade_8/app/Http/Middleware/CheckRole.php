<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if ($request->user()->role !== $role) {
            abort(403, 'Acesso não autorizado');
        }
        
        return $next($request);
    }
}