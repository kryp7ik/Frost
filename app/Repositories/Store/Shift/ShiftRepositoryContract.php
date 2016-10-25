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
}