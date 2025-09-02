<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

final class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /** @return array<string, mixed> */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),

            'auth' => [
                'user' => $request->user()
                    ? $request->user()->loadMissing('club')
                    : null,
            ],

            'ziggy' => fn (): array => [
                ...(new Ziggy())->toArray(),
                'location' => $request->url(),
            ],

            /**
             * @return array{message:mixed,type:string,data:mixed}
             */
            'flash' => fn (): array => [
                'message' => $request->session()->get('message'),
                'type' => (string) ($request->session()->get('type') ?? 'success'),
                'data' => $request->session()->get('data'),
            ],
        ];
    }
}
