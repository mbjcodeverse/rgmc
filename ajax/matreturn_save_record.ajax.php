<?php
require_once "../controllers/matreturn.controller.php";
require_once "../models/matreturn.model.php";

class saveMatreturn{
  public $trans_type; 
  public $machineid;
  public $returnby;
  public $returntype;
  public $retdate;
  public $retnumber;
  public $shift;
  public $retstatus;
  public $remarks;
  public $postedby;
  public $productlist;

  public function postSaveMatreturn(){
    $trans_type = $this->trans_type;
    $machineid = $this->machineid;
  	$returnby = $this->returnby;
    $returntype = $this->returntype;
  	$retdate = $this->retdate;
    $retnumber = $this->retnumber;
    $shift = $this->shift;
  	$retstatus = $this->retstatus;
    $remarks = $this->remarks;
  	$postedby = $this->postedby;
  	$productlist = $this->productlist;

    $data = array("machineid"=>$machineid,
                  "returnby"=>$returnby,
                  "returntype"=>$returntype,
    	            "retdate"=>$retdate,
                  "retnumber"=>$retnumber,
                  "shift"=>$shift,
                  "retstatus"=>$retstatus,
                  "remarks"=>$remarks,
                  "postedby"=>$postedby,
                  "productlist"=>$productlist);

    if ($trans_type == 'New'){
      $answer = (new ControllerMatreturn)->ctrCreateMatreturn($data);
    }else{
      $answer = (new ControllerMatreturn)->ctrEditMatreturn($data);
    }

  }
}

$mat_return = new saveMatreturn();

$mat_return -> trans_type = $_POST["trans_type"];
$mat_return -> machineid = $_POST["machineid"];
$mat_return -> returnby = $_POST["returnby"];
$mat_return -> returntype = $_POST["returntype"];
$mat_return -> retdate = $_POST["retdate"];
$mat_return -> retnumber = $_POST["retnumber"];
$mat_return -> shift = $_POST["shift"];
$mat_return -> retstatus = $_POST["retstatus"];
$mat_return -> remarks = $_POST["remarks"];
$mat_return -> postedby = $_POST["postedby"];
$mat_return -> productlist = $_POST["productlist"];
$mat_return -> postSaveMatreturn();