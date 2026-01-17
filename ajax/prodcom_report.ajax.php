<?php
require_once "../controllers/prodcom.controller.php";
require_once "../models/prodcom.model.php";

class AjaxProdcomReport{ 
   public $machineid;
   public $start_date;
   public $end_date;
   public $categorycode;   
   public $postedby;
   public $operatedby;
   public $prodstatus;
   public $reptype;

   public function ajaxDisplayProdcomReport(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $categorycode = $this->categorycode;
     $postedby = $this->postedby;
     $operatedby = $this->operatedby;
     $prodstatus = $this->prodstatus;
     $reptype = $this->reptype;

     $answer = (new ControllerProdcom)->ctrShowProdcomReport($machineid, $start_date, $end_date, $categorycode, $postedby, $operatedby, $prodstatus, $reptype);
     echo json_encode($answer);
   }
}

$prod_component = new AjaxProdcomReport();
$prod_component -> machineid = $_POST["machineid"];
$prod_component -> start_date = $_POST["start_date"];
$prod_component -> end_date = $_POST["end_date"];
$prod_component -> categorycode = $_POST["categorycode"];
$prod_component -> postedby = $_POST["postedby"];
$prod_component -> operatedby = $_POST["operatedby"];
$prod_component -> prodstatus = $_POST["prodstatus"];
$prod_component -> reptype = $_POST["reptype"];
$prod_component -> ajaxDisplayProdcomReport();