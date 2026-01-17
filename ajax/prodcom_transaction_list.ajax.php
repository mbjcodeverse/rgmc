<?php
require_once "../controllers/prodcom.controller.php";
require_once "../models/prodcom.model.php";

class AjaxProdcomTransactionList{ 
   public $machineid;
   public $start_date;
   public $end_date;   
   public $empid;
   public $prodstatus;

   public function ajaxDisplayProdcomTransactionList(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $empid = $this->empid;
     $prodstatus = $this->prodstatus;

     $answer = (new ControllerProdcom)->ctrShowProdcomTransactionList($machineid, $start_date, $end_date, $empid, $prodstatus);
     echo json_encode($answer);
   }
}

$prodcom = new AjaxProdcomTransactionList();
$prodcom -> machineid = $_POST["machineid"];
$prodcom -> start_date = $_POST["start_date"];
$prodcom -> end_date = $_POST["end_date"];
$prodcom -> empid = $_POST["empid"];
$prodcom -> prodstatus = $_POST["prodstatus"];
$prodcom -> ajaxDisplayProdcomTransactionList();