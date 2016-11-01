<?php

namespace App\Repositories\Store\Shift;

use App\Models\Store\Shift;

class EloquentShiftRepository implements ShiftRepositoryContract
{

    /**
     * Returns all Shifts within a given date range
     * @param string $startDate
     * @param string $endDate
     * @param bool $array if true return custom array of objects designed for use with fullcalendar.js
     * @return mixed
     */
    public function getAll($startDate, $endDate, $array = false)
    {
        $shifts = Shift::whereBetween('start', [date('Y-m-d\TH:i:s', strtotime($startDate)), date('Y-m-d\TH:i:s', strtotime($endDate))])->get();
        if ($array) {
            $ret = [];
            foreach ($shifts as $shift) {
                $ret[] = [
                    'start' => $shift->start,
                    'end' => $shift->end,
                    'user' => $shift->user,
                    'storeid' => $shift->store,
                    'id' => $shift->id,
                    'title' => $shift->user->name,
                    'color' => config('store.colors')[$shift->user->id],
                    'in' => $shift->in,
                    'out' => $shift->out,
                ];
            }
            return $ret;
        }
        return $shifts;
    }

    /**
     * Returns a single Shift by it's id
     * @param int $id
     * @return mixed
     */
    public function findById($id)
    {
        return Shift::where('id', $id)->first();
    }

    /**
     * Finds a shift for the designated user where the start time is sometime during the current day
     * @param int $userId
     * @return mixed Shift|bool
     */
    public function findForTodayByUser($userId)
    {
        $today = new \DateTime();
        $shift = Shift::whereBetween('start', [$today->format('Y-m-d') . 'T00:00:00', $today->format('Y-m-d') . 'T23:59:59'])
            ->where('user_id', '=', $userId)
            ->first();
        return ($shift instanceof Shift) ? $shift : false;
    }

    /**
     * Finds the shift for the designated user that is scheduled for the current day
     * If $shift->in is not set clocks the user in
     * If $shift->out is not set clocks the user out
     * @param $userId
     * @return string status to be returned for ajax request
     */
    public function clock($userId)
    {
        $shift = $this->findForTodayByUser($userId);
        if ($shift) {
            $time = new \DateTime();
            if ($shift->in == null) {
                $shift->in = $time->format('Y-m-d\TH:i:s');
                $shift->save();
                return 'Clocked in successfully';
            } elseif ($shift->out == null) {
                $shift->out = $time->format('Y-m-d\TH:i:s');
                $shift->save();
                return 'Clocked out successfully';
            } else {
                return 'The shift has already been clocked in and out';
            }
        }
        return 'Could not find a shift scheduled for you today.';
    }

    /**
     * Creates a new Shift
     * @param array $data
     * @return Shift
     */
    public function create($data)
    {
        $end = new \DateTime($data['start']);
        $end->add(new \DateInterval('PT270M'));
        $shift = new Shift([
            'user_id' => $data['user'],
            'store' => $data['storeid'],
            'start' => $data['start'],
            'end' => $end->format('Y-m-d\TH:i:s'),
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
        if($shift instanceof Shift) {
            $shift->start = (isset($data['start'])) ? $data['start'] : $shift->start;
            $shift->end = (isset($data['end'])) ? $data['end'] : $shift->end;
            $start = new \DateTime($shift->start);
            if (isset($data['in']) && strlen($data['in']) > 4) {
                $in = new \DateTime($start->format('Y-m-d\T') . ' ' . $data['in']);
                $shift->in = $in->format('Y-m-d\TH:i:s');
            }
            if (isset($data['out']) && strlen($data['out']) > 4) {
                $out = new \DateTime($start->format('Y-m-d\T') . ' ' . $data['out']);
                $shift->out = $out->format('Y-m-d\TH:i:s');
            }
            $shift->save();
        }
    }

    /**
     * Delete a Shift
     * @param int $id
     */
    public function delete($id)
    {
        $shift = $this->findById($id);
        $shift->delete();
    }
}