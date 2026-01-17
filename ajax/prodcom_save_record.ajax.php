<?php
require_once "../controllers/prodcom.controller.php";
require_once "../models/prodcom.model.php";

class saveProdcom{
  public $trans_type; 
  public $machineid;
  public $operatedby;
  public $proddate;
  public $prodnumber;
  public $shift;
  public $prodstatus;
  public $remarks;
  public $postedby;
  public $productlist;

  public function postSaveProdcom(){
    $trans_type = $this->trans_type;
    $machineid = $this->machineid;
  	$operatedby = $this->operatedby;
  	$proddate = $this->proddate;
    $prodnumber = $this->prodnumber;
    $shift = $this->shift;
  	$prodstatus = $this->prodstatus;
    $remarks = $this->remarks;
  	$postedby = $this->postedby;
  	$productlist = $this->productlist;

    $data = array("machineid"=>$machineid,
                  "operatedby"=>$operatedby,
    	          "proddate"=>$proddate,
                  "prodnumber"=>$prodnumber,
                  "shift"=>$shift,
                  "prodstatus"=>$prodstatus,
                  "remarks"=>$remarks,
                  "postedby"=>$postedby,
                  "productlist"=>$productlist);

    if ($trans_type == 'New'){
      $answer = (new ControllerProdcom)->ctrCreateProdcom($data);
    }else{
      $answer = (new ControllerProdcom)->ctrEditProdcom($data);
    }

  }
}

$prod_com = new saveProdcom();

$prod_com -> trans_type = $_POST["trans_type"];
$prod_com -> machineid = $_POST["machineid"];
$prod_com -> operatedby = $_POST["operatedby"];
$prod_com -> proddate = $_POST["proddate"];
$prod_com -> prodnumber = $_POST["prodnumber"];
$prod_com -> shift = $_POST["shift"];
$prod_com -> prodstatus = $_POST["prodstatus"];
$prod_com -> remarks = $_POST["remarks"];
$prod_com -> postedby = $_POST["postedby"];
$prod_com -> productlist = $_POST["productlist"];
$prod_com -> postSaveProdcom();