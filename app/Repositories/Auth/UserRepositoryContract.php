<?php
/**
 * Created by PhpStorm.
 * User: kryptik
 * Date: 9/14/16
 * Time: 7:19 PM
 */
namespace App\Repositories\Auth;

use App\Models\Auth\User;

interface UserRepositoryContract
{
    /**
     * Returns all users optionally includes trashed users
     * @param bool $trashed
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll($trashed = false);

    /**
     * Retrieves a single user
     * @param int $id
     * @return mixed
     */
    public function findById($id);

    /**
     * Creates a new user
     * @param array $data
     * @return User
     */
    public function create($data);

    /**
     * Updates an existing User
     * @param int $user_id
     * @param array $data
     * @return bool|mixed
     */
    public function update($user_id, $data);

    /**
     * Syncs the users Roles
     * @param User $user
     * @param array $roles
     */
    public function syncRoles(User $user, $roles);

    /**
     * Soft Deletes a user or if $force = true permanently delete the user
     * @param int $user_id
     * @param bool $force
     */
    public function delete($user_id, $force = false);

    /**
     * Restores a User that had been soft deleted
     * @param int $user_id
     */
    public function restore($user_id);
}