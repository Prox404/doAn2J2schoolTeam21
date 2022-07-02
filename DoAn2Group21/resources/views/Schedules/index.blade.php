<?php

const MONDAY = 'Monday';
const TUESDAY = 'Tuesday';
const WEDDAY = 'Wednesday';
const THURSDAY = 'Thursday';
const FRIDAY = 'Friday';
const SATURDAY = 'Saturday';
const SUNDAY = 'Sunday';

$weekdays = [2, 5, 7];
$start_date = new DateTime('2022-06-23');
$end_date = new DateTime('2022-08-23');

function getAllDaysInAMonth($year, $month, $day, $daysError = 30)
{
    $dateString = 'first ' . $day . ' of ' . $year . '-' . $month;

    if (!strtotime($dateString)) {
        throw new \Exception('"' . $dateString . '" is not a valid strtotime');
    }

    $startDay = new \DateTime($dateString);

    if ($startDay->format('j') > $daysError) {
        $startDay->modify('- 7 days');
    }

    $days = [];

    while ($startDay->format('Y-m') <= $year . '-' . str_pad($month, 2, 0, STR_PAD_LEFT)) {
        $days[] = clone $startDay;
        $startDay->modify('+ 7 days');
    }

    return $days;
}

$start_month = $start_date->format('m');
$end_month = $end_date->format('m');
$start_year = $start_date->format('Y');
$end_year = $end_date->format('Y');

$days = [];
for ($i = $start_month; $i <= $end_month; $i++) {
    foreach ($weekdays as $weekday) {
        switch ($weekday) {
            case 1:
                $day = MONDAY;
                break;
            case 2:
                $day = TUESDAY;
                break;
            case 3:
                $day = WEDDAY;
                break;
            case 4:
                $day = THURSDAY;
                break;
            case 5:
                $day = FRIDAY;
                break;
            case 6:
                $day = SATURDAY;
                break;
            case 7:
                $day = SUNDAY;
                break;
            default:
                $day = null;
                break;
        }

        $days = getAllDaysInAMonth($start_year, $i, $day);

        foreach ($days as $dayx) {
            echo $dayx->format('Y-m-d') . '<br>';
        }
    }
}

?>
