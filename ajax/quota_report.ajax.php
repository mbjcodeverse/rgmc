<?php
require_once "../controllers/prodfin.controller.php";
require_once "../models/prodfin.model.php";

class AjaxQuotaReport{ 
   public $machineid;
   public $start_date;
   public $end_date;
   public $categorycode;   
   public $etype;
   public $operatedby;
   public $prodstatus;
   public $reptype;
   public $shift;

   public function ajaxDisplayQuotaReport(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $categorycode = $this->categorycode;
     $etype = $this->etype;
     $operatedby = $this->operatedby;
     $prodstatus = $this->prodstatus;
     $reptype = $this->reptype;
     $shift = $this->shift;

     $answer = (new ControllerProdfin)->ctrShowQuotaReport($machineid, $start_date, $end_date, $categorycode, $etype, $operatedby, $prodstatus, $reptype, $shift);
     echo json_encode($answer);
   }
}

$quota_rep = new AjaxQuotaReport();
$quota_rep -> machineid = $_POST["machineid"];
$quota_rep -> start_date = $_POST["start_date"];
$quota_rep -> end_date = $_POST["end_date"];
$quota_rep -> categorycode = $_POST["categorycode"];
$quota_rep -> etype = $_POST["etype"];
$quota_rep -> operatedby = $_POST["operatedby"];
$quota_rep -> prodstatus = $_POST["prodstatus"];
$quota_rep -> reptype = $_POST["reptype"];
$quota_rep -> shift = $_POST["shift"];
$quota_rep -> ajaxDisplayQuotaReport();