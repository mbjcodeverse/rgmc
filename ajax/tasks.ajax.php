<?php
require_once "../controllers/home.controller.php";
require_once "../models/home.model.php";

class AjaxUptimeDowntimeTrend{ 
   public $reptype;
   public $buildingcode;
   public $classcode;
   public $machstatus;
   public $start_date;
   public $end_date;   

   public function ajaxDisplayUptimeDowntimeTrend(){
     $reptype = $this->reptype;
     $buildingcode = $this->buildingcode;
     $classcode = $this->classcode;
     $machstatus = $this->machstatus;
     $start_date = $this->start_date;
     $end_date = $this->end_date;

     $answer = (new ControllerHome)->ctrShowUptimeDowntimeTrend($reptype, $buildingcode, $classcode, $machstatus, $start_date, $end_date);
     echo json_encode($answer);
   }
}

$uptime_downtime = new AjaxUptimeDowntimeTrend();
$uptime_downtime -> reptype = $_POST["reptype"];
$uptime_downtime -> buildingcode = $_POST["buildingcode"];
$uptime_downtime -> classcode = $_POST["classcode"];
$uptime_downtime -> machstatus = $_POST["machstatus"];
$uptime_downtime -> start_date = $_POST["start_date"];
$uptime_downtime -> end_date = $_POST["end_date"];
$uptime_downtime -> ajaxDisplayUptimeDowntimeTrend();