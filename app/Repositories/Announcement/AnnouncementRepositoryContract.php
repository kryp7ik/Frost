<?php
/**
 * Created by PhpStorm.
 * User: kryptik
 * Date: 11/27/16
 * Time: 12:40 PM
 */
namespace App\Repositories\Announcement;

use App\Models\Announcement;

interface AnnouncementRepositoryContract
{

    /**
     * Retrieves recent announcements and stickied ones
     * @param int $limit
     * @return array $announcements['sticky' => [...], 'standard' => [...] ]
     */
    public function getAll($limit = 5);

    /**
     * Retrieves the most recent Announcements that are not sticky
     * @param int $limit
     * @return mixed
     */
    public function getStandard($limit = 5);

    /**
     * Retrieves all sticky announcements
     * @return mixed
     */
    public function getSticky();

    /**
     * @param int $id
     * @param bool $eager Eager load comments?
     * @param bool $mutate mutate the result into an array for view
     * @return mixed
     */
    public function findById($id, $eager, $mutate);

    /**
     * @param int $user_id
     * @param array $data
     * @return Announcement|bool
     */
    public function create($user_id, $data);

    /**
     * @param int $id
     * @param array $data
     * @return bool|Announcement
     */
    public function update($id, $data);

    /**
     * @param int $id
     */
    public function delete($id);

    /**
     * @param int $announcement_id
     * @param int $user_id
     * @param array $data
     * @return string
     */
    public function addComment($announcement_id, $user_id, $data);
}