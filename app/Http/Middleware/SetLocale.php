<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Supported locales in the application.
     */
    public const SUPPORTED_LOCALES = ['en', 'pt', 'hr'];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Session::get('locale');

        if (! $locale || ! in_array($locale, self::SUPPORTED_LOCALES, true)) {
            // Check browser preferences
            $preferred = $request->getPreferredLanguage(self::SUPPORTED_LOCALES);
            $locale = $preferred ?: config('app.locale', 'en');
            Session::put('locale', $locale);
        }

        App::setLocale($locale);

        return $next($request);
    }
}
