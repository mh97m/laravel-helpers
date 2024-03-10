<?php

use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use MongoDB\BSON\UTCDateTime;

if (! function_exists('uTCDateTime')) {
    /**
     * @param  $time
     */
    function uTCDateTime($date)
    {
        if (
            $date instanceof Carbon ||
            $date instanceof Verta
        ) {
            $timestamp = $date->timestamp;
        } elseif (is_string($date)) {
            if ($date[0] == '2') {
                $timestamp = Carbon::parse($date)->timezone('Asia/Tehran')->timestamp;
            } else {
                $timestamp = Verta::parse($date)->timezone('Asia/Tehran')->timestamp;
            }
        } else {
            return null;
        }

        return new UTCDateTime($timestamp * 1000);
    }
}

if (! function_exists('nowUTCDateTime')) {
    function nowUTCDateTime()
    {
        return new UTCDateTime(now()->timezone('Asia/Tehran')->getTimestamp() * 1000);
    }
}

if (! function_exists('firstDayOfNextMonthUTCDateTime')) {
    function firstDayOfNextMonthUTCDateTime()
    {
        $now = Verta::now();
        if ($now->month == 12) {
            $first_day_of_next_month = new UTCDateTime(Verta::parse($now->year + 1 .'-'. 1 .'-'. 1)->timezone('Asia/Tehran')->getTimestamp() * 1000);
        } else {
            $first_day_of_next_month = new UTCDateTime(Verta::parse($now->year.'-'.$now->month + 1 .'-'. 1)->timezone('Asia/Tehran')->getTimestamp() * 1000);
        }

        return $first_day_of_next_month;
    }
}

if (! function_exists('firstDayOfMonthUTCDateTime')) {
    function firstDayOfMonthUTCDateTime()
    {
        $now = Verta::now();
        $first_day_of_month = new UTCDateTime(Verta::parse($now->year.'-'.$now->month.'-'. 1)->timezone('Asia/Tehran')->getTimestamp() * 1000);

        return $first_day_of_month;
    }
}

if (! function_exists('vertaToCrabon')) {
    function vertaToCrabon($date)
    {
        try {
            $exploded_date = explode('/', $date);

            return Verta::parse(trim($exploded_date[2]).'-'.trim($exploded_date[1]).'-'.trim($exploded_date[0]))->timezone('Asia/Tehran')->toCarbon()->format('Y/m/d');
        } catch (\Exception $e) {
            return $date;
        }
    }
}

if (! function_exists('reverseTimeFormat')) {
    function reverseTimeFormat($date)
    {
        try {
            $exploded_date = explode('/', $date);

            return trim($exploded_date[2]).'/'.trim($exploded_date[1]).'/'.trim($exploded_date[0]);
        } catch (\Exception $e) {
            return $date;
        }
    }
}
