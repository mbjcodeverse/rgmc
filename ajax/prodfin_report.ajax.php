<?php
require_once "../controllers/prodfin.controller.php";
require_once "../models/prodfin.model.php";

class AjaxProdfinReport{ 
   public $machineid;
   public $start_date;
   public $end_date;
   public $categorycode;   
   public $postedby;
   public $operatedby;
   public $prodstatus;
   public $reptype;
   public $shift;

   public function ajaxDisplayProdfinReport(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $categorycode = $this->categorycode;
     $postedby = $this->postedby;
     $operatedby = $this->operatedby;
     $prodstatus = $this->prodstatus;
     $reptype = $this->reptype;
     $shift = $this->shift;

     $answer = (new ControllerProdfin)->ctrShowProdfinReport($machineid, $start_date, $end_date, $categorycode, $postedby, $operatedby, $prodstatus, $reptype, $shift);
     echo json_encode($answer);
   }
}

$prod_fin = new AjaxProdfinReport();
$prod_fin -> machineid = $_POST["machineid"];
$prod_fin -> start_date = $_POST["start_date"];
$prod_fin -> end_date = $_POST["end_date"];
$prod_fin -> categorycode = $_POST["categorycode"];
$prod_fin -> postedby = $_POST["postedby"];
$prod_fin -> operatedby = $_POST["operatedby"];
$prod_fin -> prodstatus = $_POST["prodstatus"];
$prod_fin -> reptype = $_POST["reptype"];
$prod_fin -> shift = $_POST["shift"];
$prod_fin -> ajaxDisplayProdfinReport();