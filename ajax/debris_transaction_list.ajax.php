<?php
require_once "../controllers/debris.controller.php";
require_once "../models/debris.model.php";

class AjaxDebrisTransactionList{ 
   public $machineid;
   public $start_date;
   public $end_date;   
   public $empid;
   public $debstatus;

   public function ajaxDisplayDebrisTransactionList(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $empid = $this->empid;
     $debstatus = $this->debstatus;

     $answer = (new ControllerDebris)->ctrShowDebrisTransactionList($machineid, $start_date, $end_date, $empid, $debstatus);
     echo json_encode($answer);
   }
}

$debris_materials = new AjaxDebrisTransactionList();
$debris_materials -> machineid = $_POST["machineid"];
$debris_materials -> start_date = $_POST["start_date"];
$debris_materials -> end_date = $_POST["end_date"];
$debris_materials -> empid = $_POST["empid"];
$debris_materials -> debstatus = $_POST["debstatus"];
$debris_materials -> ajaxDisplayDebrisTransactionList();