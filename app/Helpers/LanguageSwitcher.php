<?php

declare(strict_types=1);

namespace Morris\Core\Helpers;

use Illuminate\Http\Request;

class LanguageSwitcher
{
    public static function setLocale(Request $request): void
    {
        $requestedLocale = $request->header("Accept-Language");
        $preferredLocale = $request->user()?->preferred_language;
        $supportedLocales = config("morris.supported_locales") ?? [];

        if ($requestedLocale && in_array($requestedLocale, $supportedLocales, true)) {
            $locale = $requestedLocale;
        } else if ($preferredLocale && in_array($preferredLocale, $supportedLocales, true)) {
            $locale = $request->user()->preferred_language;
        } else {
            $locale = config("app.locale");
        }

        app()->setLocale($locale);
    }
}
