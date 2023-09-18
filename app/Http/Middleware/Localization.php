<?php

declare(strict_types=1);

namespace Morris\Core\Http\Middleware;

use Closure;
use Morris\Core\Helpers\LanguageSwitcher;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    public function __construct(
        protected Application $application,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        LanguageSwitcher::setLocale($request);

        return $next($request);
    }
}
