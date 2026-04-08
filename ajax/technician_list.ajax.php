<?php
require_once "../controllers/machinetracking.controller.php";
require_once "../models/machinetracking.model.php";

class TechnicianInfoList{ 
   public $machineid;
   public $datemode;
   public $start_date;
   public $end_date;
//    public $technician;

   public function DisplayTechnicianInfoList(){
     $machineid = $this->machineid;
     $datemode = $this->datemode;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
    //  $technician = $this->technician;

     $answer = (new ControllerMachineTracking)->ctrTechnicianList($machineid, $datemode, $start_date, $end_date);
     echo json_encode($answer);
   }
}

$technician_list = new TechnicianInfoList();
$technician_list -> machineid = $_POST["machineid"];
$technician_list -> datemode = $_POST["datemode"];
$technician_list -> start_date = $_POST["start_date"];
$technician_list -> end_date = $_POST["end_date"];
// $technician_list -> technician = $_POST["technician"];
$technician_list -> DisplayTechnicianInfoList();