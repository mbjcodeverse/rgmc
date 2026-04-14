<?php
require_once "../controllers/calendar.controller.php";
require_once "../models/calendar.model.php";

class AjaxCalendarList{ 
   public $start_date;
   public $end_date;   

   public function ajaxDisplayCalendarList(){
     $start_date = $this->start_date;
     $end_date = $this->end_date;

     $answer = (new ControllerCalendar)->ctrMaintenanceCalendarList($start_date, $end_date);
     echo json_encode($answer);
   }
}

$calendar_list = new AjaxCalendarList();
$calendar_list -> start_date = $_POST["start_date"];
$calendar_list -> end_date = $_POST["end_date"];
$calendar_list -> ajaxDisplayCalendarList();