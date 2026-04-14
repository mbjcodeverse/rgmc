<?php
class ControllerCalendar{
    static public function ctrMaintenanceCalendarList($start_date, $end_date){
		$answer = (new ModelCalendar)->mdlMaintenanceCalendarList($start_date, $end_date);
		return $answer;
	}
}