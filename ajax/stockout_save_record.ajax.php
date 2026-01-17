<?php
require_once "../controllers/stockout.controller.php";
require_once "../models/stockout.model.php";

class saveStockout{
  public $trans_type; 
  public $inccode;
  public $reltype;
  public $deptcode;
  public $machineid;
  public $requestby;
  public $reqdate;
  public $reqnumber;
  public $shift;
  public $reqstatus;
  public $remarks;
  public $postedby;
  public $productlist;

  public function postSavedStockout(){
    $trans_type = $this->trans_type;
    $inccode = $this->inccode;
    $reltype = $this->reltype;
    $deptcode = $this->deptcode;
    $machineid = $this->machineid;
  	$requestby = $this->requestby;
  	$reqdate = $this->reqdate;
    $reqnumber = $this->reqnumber;
    $shift = $this->shift;
  	$reqstatus = $this->reqstatus;
    $remarks = $this->remarks;
  	$postedby = $this->postedby;
  	$productlist = $this->productlist;

    $data = array("inccode"=>$inccode,
                  "reltype"=>$reltype,
                  "deptcode"=>$deptcode,
                  "machineid"=>$machineid,
                  "requestby"=>$requestby,
    	            "reqdate"=>$reqdate,
                  "reqnumber"=>$reqnumber,
                  "shift"=>$shift,
                  "reqstatus"=>$reqstatus,
                  "remarks"=>$remarks,
                  "postedby"=>$postedby,
                  "productlist"=>$productlist);

    if ($trans_type == 'New'){
      $answer = (new ControllerStockout)->ctrCreateStockout($data);
    }else{
      $answer = (new ControllerStockout)->ctrEditStockout($data);
    }

  }
}

$withdrawal = new saveStockout();

$withdrawal -> trans_type = $_POST["trans_type"];
$withdrawal -> inccode = $_POST["inccode"];
$withdrawal -> reltype = $_POST["reltype"];
$withdrawal -> deptcode = $_POST["deptcode"];
$withdrawal -> machineid = $_POST["machineid"];
$withdrawal -> requestby = $_POST["requestby"];
$withdrawal -> reqdate = $_POST["reqdate"];
$withdrawal -> reqnumber = $_POST["reqnumber"];
$withdrawal -> shift = $_POST["shift"];
$withdrawal -> reqstatus = $_POST["reqstatus"];
$withdrawal -> remarks = $_POST["remarks"];
$withdrawal -> postedby = $_POST["postedby"];
$withdrawal -> productlist = $_POST["productlist"];
$withdrawal -> postSavedStockout();