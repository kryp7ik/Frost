<?php

namespace App\Repositories\Store\Shift;

use App\Models\Store\Shift;

class EloquentShiftRepository implements ShiftRepositoryContract
{

    /**
     * Returns all Shifts within a given date range
     * @param string $startDate
     * @param string $endDate
     * @return mixed
     */
    public function getAll($startDate, $endDate)
    {
        $shifts = Shift::whereBetween('start', [strtotime($startDate), strtotime($endDate)])->get();
        return $shifts;
    }

    /**
     * Returns a single Shift by it's id
     * @param int $id
     * @return mixed
     */
    public function findById($id)
    {
        return Shift::where('id', $id)->firstOrFail();
    }

    /**
     * Creates a new Shift
     * @param array $data
     * @return Shift
     */
    public function create($data)
    {
        $shift = new Shift([
            'user_id' => $data['user'],
            'store' => $data['store'],
            'start' => $data['start'],
            'end' => $data['end']
        ]);
        $shift->save();
        return $shift;
    }

    /**
     * Update an existing Shift
     * @param int $id
     * @param array $data
     */
    public function update($id, $data)
    {
        $shift = $this->findById($id);

    }
}