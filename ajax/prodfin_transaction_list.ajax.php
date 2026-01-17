<?php
require_once "../controllers/prodfin.controller.php";
require_once "../models/prodfin.model.php";

class AjaxProdfinTransactionList{ 
   public $machineid;
   public $start_date;
   public $end_date;   
   public $empid;
   public $prodstatus;

   public function ajaxDisplayProdfinTransactionList(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $empid = $this->empid;
     $prodstatus = $this->prodstatus;

     $answer = (new ControllerProdfin)->ctrShowProdfinTransactionList($machineid, $start_date, $end_date, $empid, $prodstatus);
     echo json_encode($answer);
   }
}

$prodfin = new AjaxProdfinTransactionList();
$prodfin -> machineid = $_POST["machineid"];
$prodfin -> start_date = $_POST["start_date"];
$prodfin -> end_date = $_POST["end_date"];
$prodfin -> empid = $_POST["empid"];
$prodfin -> prodstatus = $_POST["prodstatus"];
$prodfin -> ajaxDisplayProdfinTransactionList();