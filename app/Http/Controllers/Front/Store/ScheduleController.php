<?php

namespace App\Http\Controllers\Front\Store;

use App\Http\Controllers\Controller;
use App\Repositories\Auth\UserRepositoryContract;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ScheduleController extends Controller
{
    public function home(UserRepositoryContract $userRepo): InertiaResponse
    {
        $users = $userRepo->getAll();

        return Inertia::render('Schedule/Home', [
            'users' => collect($users)->map(fn ($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'store' => $u->store,
                'color' => config('store.colors')[$u->id] ?? null,
            ])->values(),
            'stores' => collect(config('store.stores'))->map(fn ($name, $id) => [
                'id' => (int) $id,
                'name' => $name,
            ])->values(),
        ]);
    }

    public function warning(): InertiaResponse
    {
        return Inertia::render('Schedule/Warning');
    }
}
