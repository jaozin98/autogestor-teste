<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Usuário não autenticado'], 401);
            }
            abort(401, 'Usuário não autenticado');
        }

        if (!Auth::user()->can($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Acesso negado - Permissão insuficiente'], 403);
            }
            abort(403, 'Acesso negado - Permissão insuficiente');
        }

        return $next($request);
    }
}
