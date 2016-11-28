<?php

namespace App\Repositories\Auth;


use App\Models\Auth\User;
use Illuminate\Support\Facades\Hash;

class EloquentUserRepository implements UserRepositoryContract
{

    /**
     * Returns all users optionally includes trashed users
     * @param bool $trashed
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll($trashed = false)
    {
        if ($trashed) {
            $users = User::withTrashed()->get();
        } else {
            $users = User::all();
        }
        return $users;
    }

    /**
     * Retrieves a single user
     * @param int $id
     * @return mixed
     */
    public function findById($id)
    {
        return User::withTrashed()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * Creates a new user
     * @param array $data
     * @return User
     */
    public function create($data)
    {
        $user = new User([
            'name' => $data['name'],
            'email' => $data['email'],
            'store' => $data['store'],
            'password' => Hash::make($data['password'])
        ]);
        $user->save();
        $this->syncRoles($user, $data['role']);
        flash('A user has been successfully created', 'success');
        return $user;
    }

    /**
     * Updates an existing User
     * @param int $user_id
     * @param array $data
     * @return bool|mixed
     */
    public function update($user_id, $data)
    {
        $user = $this->findById($user_id);
        $user->name = $data['name'];
        $user->email = $data['email'];
        if(isset($data['store'])) $user->store = $data['store'];
        if ($data['password'] != '') {
            $user->password = Hash::make($data['password']);
        }
        $user->save();
        if(isset($data['role'])) $this->syncRoles($user, $data['role']);
        flash('The user has been successfully updated', 'success');
        return $user;

    }

    /**
     * Syncs the users Roles
     * @param User $user
     * @param array $roles
     */
    public function syncRoles(User $user, $roles)
    {
        if(!empty($roles)) {
            $user->roles()->sync($roles);
        } else {
            $user->roles()->detach();
        }
    }

    /**
     * Soft Deletes a user or if $force = true permanently delete the user
     * @param int $user_id
     * @param bool $force
     */
    public function delete($user_id, $force = false)
    {
        $user = $this->findById($user_id);
        if ($force) {
            $user->forceDelete();
            flash('The user has been permanently deleted', 'success');
        } else {
            $user->delete();
            flash('The user has been soft deleted', 'success');
        }
    }

    /**
     * Restores a User that had been soft deleted
     * @param int $user_id
     */
    public function restore($user_id)
    {
        $user = $this->findById($user_id);
        if ($user) {
            $user->restore();
            flash('The user has been restored', 'success');
        }
    }

}