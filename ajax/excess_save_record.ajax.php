<?php
require_once "../controllers/excess.controller.php";
require_once "../models/excess.model.php";

class saveExcess{
  public $trans_type; 
  public $machineid;
  public $operatedby;
  public $excdate;
  public $excnumber;
  public $shift;
  public $excstatus;
  public $remarks;
  public $postedby;
  public $productlist;

  public function postSaveExcess(){
    $trans_type = $this->trans_type;
    $machineid = $this->machineid;
  	$operatedby = $this->operatedby;
  	$excdate = $this->excdate;
    $excnumber = $this->excnumber;
    $shift = $this->shift;
  	$excstatus = $this->excstatus;
    $remarks = $this->remarks;
  	$postedby = $this->postedby;
  	$productlist = $this->productlist;

    $data = array("machineid"=>$machineid,
                  "operatedby"=>$operatedby,
    	          "excdate"=>$excdate,
                  "excnumber"=>$excnumber,
                  "shift"=>$shift,
                  "excstatus"=>$excstatus,
                  "remarks"=>$remarks,
                  "postedby"=>$postedby,
                  "productlist"=>$productlist);

    if ($trans_type == 'New'){
      $answer = (new ControllerExcess)->ctrCreateExcess($data);
    }else{
      $answer = (new ControllerExcess)->ctrEditExcess($data);
    }

  }
}

$excess_materials = new saveExcess();

$excess_materials -> trans_type = $_POST["trans_type"];
$excess_materials -> machineid = $_POST["machineid"];
$excess_materials -> operatedby = $_POST["operatedby"];
$excess_materials -> excdate = $_POST["excdate"];
$excess_materials -> excnumber = $_POST["excnumber"];
$excess_materials -> shift = $_POST["shift"];
$excess_materials -> excstatus = $_POST["excstatus"];
$excess_materials -> remarks = $_POST["remarks"];
$excess_materials -> postedby = $_POST["postedby"];
$excess_materials -> productlist = $_POST["productlist"];
$excess_materials -> postSaveExcess();