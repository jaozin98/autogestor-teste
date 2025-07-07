<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;


class BlockAdminFromCRUD
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (Auth::check() && $user && $user->hasRole('admin')) {
            // Verificar se a rota é relacionada a produtos, categorias ou marcas
            $routeName = $request->route()->getName();

            if (
                str_starts_with($routeName, 'products.') ||
                str_starts_with($routeName, 'categories.') ||
                str_starts_with($routeName, 'brands.')
            ) {

                return redirect()->route('home')->with('error', 'Usuários administradores não têm acesso a esta funcionalidade.');
            }
        }

        return $next($request);
    }
}
