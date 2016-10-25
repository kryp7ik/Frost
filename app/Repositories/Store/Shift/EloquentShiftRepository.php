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
            $start = new \DateTime($shift->start);
            $shift->start = (isset($data['start'])) ? $data['start'] : $shift->start;
            $shift->end = (isset($data['end'])) ? $data['end'] : $shift->end;
            $shift->in = (isset($data['in'])) ? $start->format('Y-m-d\T') . $data['in'] : $shift->in;
            $shift->out = (isset($data['out'])) ? $data['out'] : $shift->out;
            $shift->save();
        }
    }
}