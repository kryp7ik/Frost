<?php

namespace App\Helpers;

class DateHelper {

    /**
     * Accepts any DateTime string and returns the time elapsed (ex. 4 days ago, etc...)
     * @param string $dateTime
     * @return string
     */
    public static function timeElapsed($dateTime)
    {
        $date = strtotime($dateTime);
        $elapsed = time() - $date;
        $days = $elapsed / 60 / 60 / 24;
        if ($days < 1) {
            $ret = 'Today';
        } else {
            $ret = round($days) . ' days ago';
        }
        return $ret;
    }
}