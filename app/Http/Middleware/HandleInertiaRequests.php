<?php

namespace App\Http\Middleware;

use App\Services\Store\SuspendedOrders;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        $stores = config('store.stores', []);

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'store' => $user->store,
                    'store_name' => $user->store && isset($stores[$user->store])
                        ? $stores[$user->store]
                        : null,
                    'is_manager' => $user->hasRole('manager'),
                    'is_admin' => $user->hasRole('admin'),
                    'two_factor_enabled' => ! is_null($user->two_factor_confirmed_at),
                ] : null,
            ],
            'flash' => [
                'message' => fn () => $request->session()->get('flash_notification.0.message'),
                'type' => fn () => $request->session()->get('flash_notification.0.level', 'info'),
            ],
            'suspendedOrders' => fn () => $user
                ? app(SuspendedOrders::class)->getSuspendedOrders()->map(fn ($order) => [
                    'id' => $order->id,
                    'total' => (float) $order->total,
                ])->values()
                : [],
        ]);
    }
}
