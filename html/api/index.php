<?php declare(strict_types=1);
define('ROOT','/var/www');
require ROOT . '/vendor/autoload.php';
use Carbon\Carbon;

$tz = "America/New_York";
$file = ROOT . '/data/input.json';

$data = file_get_contents($file);
$json = json_decode($data);

$today = new Carbon($tz);

# calculate next holiday
$holidays = $json->holidays;
$todayIsHoliday = false;
$nextHoliday = "none";
$nextHolidayDate = "00000000";
$nextHolidayRemaining = "0";

foreach ($holidays as $holiday) {
	$dt = new Carbon($holiday->date, $tz);
    $dt->setHour(15);

	if ($dt->isPast()) {
		$key = array_search($holiday, $holidays);
		unset($holidays[$key]);
	}
    elseif ($dt->isToday()) {
        $key = array_search($holiday, $holidays);
        unset($holidays[$key]);
        $todayIsHoliday = true;
    }
	else {
		$nextHoliday = $holiday->name;
		$nextHolidayDate = $dt->toFormattedDayDateString();
		$nextHolidayRemaining = $today->diffInWeekdays($dt);
        $nextHolidayRemaining -= 1;
		break;
	}
}

# calculate last day
$daysOff = 0;

foreach ($holidays as $holiday) {
	$daysOff += $holiday->duration;
}

$lastDay = new Carbon($json->last_day, $tz);
$lastDay->hour = 15;
$lastDayRemaining = $today->diffInWeekdays($lastDay);
$lastDayRemaining -= $daysOff;

$output = array(
	"today" => $today->toFormattedDayDateString(),
    "today_is_holiday" => $todayIsHoliday,
	"next_holiday" => $nextHoliday,
	"next_holiday_date" => $nextHolidayDate,
	"next_holiday_remaining" => $nextHolidayRemaining,
	"last_day" => $lastDay->toFormattedDayDateString(),
	"last_day_remaining" => $lastDayRemaining,
	"last_day_passed" => ($lastDay->isPast())
);

header("X-Robots-Tag: noindex, nofollow", true);
header("Content-Type: application/json");
echo json_encode($output);
exit();




