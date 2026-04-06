<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RoleFormRequest;
use App\Models\Auth\Role;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class RolesController extends Controller
{
    public function index(): InertiaResponse
    {
        return Inertia::render('Admin/Roles/Index', [
            'roles' => Role::all(['id', 'name', 'display_name', 'description'])
                ->map(fn ($r) => [
                    'id' => $r->id,
                    'name' => $r->name,
                    'display_name' => $r->display_name,
                    'description' => $r->description,
                ])->values(),
        ]);
    }

    public function create(): InertiaResponse
    {
        return Inertia::render('Admin/Roles/Create');
    }

    public function store(RoleFormRequest $request): RedirectResponse
    {
        Role::create([
            'name' => $request->get('name'),
            'display_name' => $request->get('display_name'),
            'description' => $request->get('description'),
        ]);

        return redirect('/admin/roles')->with('flash_notification.0.message', 'A new role has been created.');
    }
}
