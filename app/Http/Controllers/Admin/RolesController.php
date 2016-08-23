<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleFormRequest;
use App\Models\Auth\Role;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('backend.roles.index', ['roles' => $roles]);
    }
    public function create()
    {
        return view('backend.roles.create');
    }

    public function store(RoleFormRequest $request)
    {
        $role = new Role(array(
            'name' => $request->get('name'),
            'display_name' => $request->get('display_name'),
            'description' => $request->get('description')
        ));
        $role->save();
        return redirect('/admin/roles/create')->with('status', 'A new role has been created.');
    }
}
