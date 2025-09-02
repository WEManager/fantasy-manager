<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

final class HandleTheme
{
    public function handle(Request $request, Closure $next): Response
    {
        View::share('theme', $request->cookie('theme') ?? 'system');

        return $next($request);
    }
}
