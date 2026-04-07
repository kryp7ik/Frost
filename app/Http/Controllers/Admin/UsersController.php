<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserFormRequest;
use App\Models\Auth\Role;
use App\Repositories\Auth\UserRepositoryContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class UsersController extends Controller
{
    public function __construct(protected UserRepositoryContract $userRepo)
    {
    }

    public function index(Request $request): InertiaResponse
    {
        $includeTrashed = $request->input('trashed') === 'true';
        $users = $this->userRepo->getAll($includeTrashed);

        return Inertia::render('Admin/Users/Index', [
            'users' => collect($users)->map(fn ($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'store' => $u->store,
                'deleted_at' => $u->deleted_at,
                'roles' => $u->roles->pluck('name')->values(),
            ])->values(),
            'includeTrashed' => $includeTrashed,
        ]);
    }

    public function create(): InertiaResponse
    {
        return Inertia::render('Admin/Users/Create', [
            'roles' => Role::all(['id', 'name', 'display_name'])->map(fn ($r) => [
                'id' => $r->id,
                'name' => $r->name,
                'display_name' => $r->display_name,
            ])->values(),
            'stores' => collect(config('store.stores'))->map(fn ($name, $id) => [
                'id' => (int) $id,
                'name' => $name,
            ])->values(),
        ]);
    }

    public function store(UserFormRequest $request): RedirectResponse
    {
        $this->userRepo->create($request->all());

        return redirect('/admin/users');
    }

    public function edit(int $id): InertiaResponse
    {
        $user = $this->userRepo->findById($id);

        return Inertia::render('Admin/Users/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'store' => $user->store,
                'role_ids' => $user->roles->pluck('id')->values(),
            ],
            'roles' => Role::all(['id', 'name', 'display_name'])->map(fn ($r) => [
                'id' => $r->id,
                'name' => $r->name,
                'display_name' => $r->display_name,
            ])->values(),
            'stores' => collect(config('store.stores'))->map(fn ($name, $id) => [
                'id' => (int) $id,
                'name' => $name,
            ])->values(),
        ]);
    }

    public function update(int $id, UserFormRequest $request): RedirectResponse
    {
        $this->userRepo->update($id, $request->all());

        return redirect('/admin/users');
    }

    public function delete(int $id): RedirectResponse
    {
        $this->userRepo->delete($id);

        return redirect('/admin/users');
    }

    public function restore(int $id): RedirectResponse
    {
        $this->userRepo->restore($id);

        return redirect('/admin/users');
    }
}
