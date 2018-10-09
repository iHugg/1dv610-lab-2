<?php
namespace view;

class DateTimeView {


	public function show() : string {
    date_default_timezone_set('Europe/Stockholm');
    $timeString = date("l"). ", the " .date("jS"). " of " .date("F Y"). ", The time is " .date("H:i:s");

		return '<p>' . $timeString . '</p>';
	}
}