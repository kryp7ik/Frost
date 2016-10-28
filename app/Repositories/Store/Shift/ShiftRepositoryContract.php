<?php

namespace App\Repositories\Store\Shift;

/**
 * Created by PhpStorm.
 * User: kryptik
 * Date: 10/3/16
 * Time: 5:07 PM
 */
interface ShiftRepositoryContract
{
    /**
     * Returns all Shifts within a given date range
     * @param string $startDate
     * @param string $endDate
     * @param bool $array if true return custom array of objects
     * @return mixed
     */
    public function getAll($startDate, $endDate, $array = false);

    /**
     * Returns a single Shift by it's id
     * @param int $id
     * @return mixed
     */
    public function findById($id);

    /**
     * Finds a shift for the designated user where the start time is sometime during the current day
     * @param int $userId
     * @return mixed Shift|bool
     */
    public function findForTodayByUser($userId);

    /**
     * Finds the shift for the designated user that is scheduled for the current day
     * If $shift->in is not set clocks the user in
     * If $shift->out is not set clocks the user out
     * @param $userId
     * @return string status to be returned for ajax request
     */
    public function clock($userId);

    /**
     * Creates a new Shift
     * @param array $data
     * @return \App\Models\Store\Shift
     */
    public function create($data);

    /**
     * Update an existing Shift
     * @param int $id
     * @param array $data
     */
    public function update($id, $data);

    /**
     * Delete a Shift
     * @param int $id
     */
    public function delete($id);

}