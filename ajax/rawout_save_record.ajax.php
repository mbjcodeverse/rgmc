<?php
require_once "../controllers/rawout.controller.php";
require_once "../models/rawout.model.php";

class saveRawout{
  public $trans_type; 
  public $machineid;
  public $requestby;
  public $reqdate;
  public $reqnumber;
  public $shift;
  public $reqstatus;
  public $remarks;
  public $postedby;
  public $productlist;

  public function postSaveRawout(){
    $trans_type = $this->trans_type;
    $machineid = $this->machineid;
  	$requestby = $this->requestby;
  	$reqdate = $this->reqdate;
    $reqnumber = $this->reqnumber;
    $shift = $this->shift;
  	$reqstatus = $this->reqstatus;
    $remarks = $this->remarks;
  	$postedby = $this->postedby;
  	$productlist = $this->productlist;

    $data = array("machineid"=>$machineid,
                  "requestby"=>$requestby,
    	          "reqdate"=>$reqdate,
                  "reqnumber"=>$reqnumber,
                  "shift"=>$shift,
                  "reqstatus"=>$reqstatus,
                  "remarks"=>$remarks,
                  "postedby"=>$postedby,
                  "productlist"=>$productlist);

    if ($trans_type == 'New'){
      $answer = (new ControllerRawout)->ctrCreateRawout($data);
    }else{
      $answer = (new ControllerRawout)->ctrEditRawout($data);
    }

  }
}

$withdrawal = new saveRawout();

$withdrawal -> trans_type = $_POST["trans_type"];
$withdrawal -> machineid = $_POST["machineid"];
$withdrawal -> requestby = $_POST["requestby"];
$withdrawal -> reqdate = $_POST["reqdate"];
$withdrawal -> reqnumber = $_POST["reqnumber"];
$withdrawal -> shift = $_POST["shift"];
$withdrawal -> reqstatus = $_POST["reqstatus"];
$withdrawal -> remarks = $_POST["remarks"];
$withdrawal -> postedby = $_POST["postedby"];
$withdrawal -> productlist = $_POST["productlist"];
$withdrawal -> postSaveRawout();