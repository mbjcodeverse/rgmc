<?php
require_once "../controllers/return.controller.php";
require_once "../models/return.model.php";

class saveReturn{
  public $trans_type; 
  public $returnby;
  public $retdate;
  public $retnumber;
  public $retstatus;
  public $remarks;
  public $postedby;
  public $productlist;

  public function postSavedReturn(){
    $trans_type = $this->trans_type;
  	$returnby = $this->returnby;
  	$retdate = $this->retdate;
    $retnumber = $this->retnumber;
  	$retstatus = $this->retstatus;
    $remarks = $this->remarks;
  	$postedby = $this->postedby;
  	$productlist = $this->productlist;

    $data = array("returnby"=>$returnby,
                  "retstatus"=>$retstatus,
                  "retdate"=>$retdate,
                  "retnumber"=>$retnumber,
                  "remarks"=>$remarks,
                  "postedby"=>$postedby,
                  "productlist"=>$productlist);

    if ($trans_type == 'New'){
      $answer = (new ControllerReturn)->ctrAddReturn($data);
    }else{
      $answer = (new ControllerReturn)->ctrEditReturn($data);
    }

  }
}

$return_trans = new saveReturn();

$return_trans -> trans_type = $_POST["trans_type"];
$return_trans -> returnby = $_POST["returnby"];
$return_trans -> retdate = $_POST["retdate"];
$return_trans -> retnumber = $_POST["retnumber"];
$return_trans -> retstatus = $_POST["retstatus"];
$return_trans -> remarks = $_POST["remarks"];
$return_trans -> postedby = $_POST["postedby"];
$return_trans -> productlist = $_POST["productlist"];
$return_trans -> postSavedReturn();