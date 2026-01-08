<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }
        $user = auth()->user();
        $role = strtolower((string)($user->role ?? ''));
        if (!in_array($role, ['admin','owner'], true)) {
            return redirect('/');
        }
        return $next($request);
    }
}
