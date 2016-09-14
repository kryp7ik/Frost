<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\Role;
use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Repositories\Auth\UserRepositoryContract;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserEditFormRequest;

class UsersController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepositoryContract $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index()
    {
        $users = $this->userRepo->getAll();
        return view('backend.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('backend.users.create', compact('roles'));
    }

    public function store()
    {

    }

    public function edit($id)
    {
        $user = $this->userRepo->findById($id);
        $roles = Role::all();
        $selectedRoles = $user->roles->lists('id')->toArray();
        return view('backend.users.edit', compact('user', 'roles', 'selectedRoles'));
    }

    public function update($id, UserEditFormRequest $request)
    {
        $user = $this->userRepo->update($id, $request->all());
        return back();
    }
}
