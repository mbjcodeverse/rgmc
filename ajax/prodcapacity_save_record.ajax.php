<?php
require_once "../controllers/prodfin.controller.php";
require_once "../models/prodfin.model.php";

class saveProdcapacity{
  public $trans_type; 
  public $machineid;
  public $etype;
  public $capacitynumber;
  public $remarks;
  public $postedby;
  public $productlist;

  public function postSaveProdcapacity(){
    $trans_type = $this->trans_type;
    $machineid = $this->machineid;
    $etype = $this->etype;
    $capacitynumber = $this->capacitynumber;
    $remarks = $this->remarks;
  	$postedby = $this->postedby;
  	$productlist = $this->productlist;

    $data = array("machineid"=>$machineid,
                  "etype"=>$etype,
                  "capacitynumber"=>$capacitynumber,
                  "remarks"=>$remarks,
                  "postedby"=>$postedby,
                  "productlist"=>$productlist);

    if ($trans_type == 'New'){
      $answer = (new ControllerProdfin)->ctrCreateProdcapacity($data);
    }else{
      $answer = (new ControllerProdfin)->ctrEditProdcapacity($data);
    }

  }
}

$prod_capacity = new saveProdcapacity();

$prod_capacity -> trans_type = $_POST["trans_type"];
$prod_capacity -> machineid = $_POST["machineid"];
$prod_capacity -> etype = $_POST["etype"];
$prod_capacity -> capacitynumber = $_POST["capacitynumber"];
$prod_capacity -> remarks = $_POST["remarks"];
$prod_capacity -> postedby = $_POST["postedby"];
$prod_capacity -> productlist = $_POST["productlist"];

$prod_capacity -> postSaveProdcapacity();