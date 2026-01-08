<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = null;

        $adminLocale = null;
        try {
            $path = 'settings.json';
            if (Storage::disk('local')->exists($path)) {
                $raw = Storage::disk('local')->get($path);
                $data = json_decode($raw, true);
                if (is_array($data)) {
                    $adminLocale = $data['admin']['language'] ?? null;
                }
            }
        } catch (\Throwable $e) {
        }

        if (str_starts_with($request->path(), 'admin')) {
            $locale = Session::get('locale') ?: ($adminLocale ?: 'vi');
        } else {
            $locale = Session::get('locale') ?: config('app.locale');
        }

        if (is_string($locale) && $locale !== '') {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
