<?php

namespace App\Http\Controllers\Admin;

use App\Role;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserEditFormRequest;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('backend.users.index', ['users' => $users]);
    }

    public function edit($id)
    {
        $user = User::whereId($id)->firstOrFail();
        $roles = Role::all();
        $selectedRoles = $user->roles->lists('id')->toArray();
        return view('backend.users.edit', compact('user', 'roles', 'selectedRoles'));
    }

    public function update($id, UserEditFormRequest $request)
    {
        $user = User::whereId($id)->firstOrFail();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->store = $request->get('store');
        $password = $request->get('password');
        if ($password != '') {
            $user->password = Hash::make($password);
        }
        $user->save();
        $user->saveRoles($request->get('role'));
        return redirect(action('Admin\UsersController@edit', $user->id))->with('status', 'The user has been updated.');
    }
}
