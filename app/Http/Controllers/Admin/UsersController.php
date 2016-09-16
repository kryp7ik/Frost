<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Auth\Role;
use App\Http\Controllers\Controller;
use App\Repositories\Auth\UserRepositoryContract;
use App\Http\Requests\Auth\UserFormRequest;

class UsersController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepositoryContract $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index(Request $request)
    {
        if ($request->input('trashed') == 'true') {
            $users = $this->userRepo->getAll(true);
        } else {
            $users = $this->userRepo->getAll();
        }
        return view('backend.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('backend.users.create', compact('roles'));
    }

    public function store(UserFormRequest $request)
    {
        $this->userRepo->create($request->all());
        return redirect('/admin/users');
    }

    public function edit($id)
    {
        $user = $this->userRepo->findById($id);
        $roles = Role::all();
        $selectedRoles = $user->roles->lists('id')->toArray();
        return view('backend.users.edit', compact('user', 'roles', 'selectedRoles'));
    }

    public function update($id, UserFormRequest $request)
    {
        $this->userRepo->update($id, $request->all());
        return redirect('/admin/users');
    }

    public function delete($id)
    {
        $this->userRepo->delete($id);
        return redirect('/admin/users');
    }

    public function restore($id)
    {
        $this->userRepo->restore($id);
        return redirect('/admin/users');
    }
}
