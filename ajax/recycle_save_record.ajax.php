<?php
require_once "../controllers/recycle.controller.php";
require_once "../models/recycle.model.php";

class saveRecycle{
  public $trans_type; 
  public $machineid;
  public $recycleby;
  public $recdate;
  public $recnumber;
  public $shift;
  public $recstatus;
  public $remarks;
  public $postedby;
  public $productlist;

  public function postSaveRecycle(){
    $trans_type = $this->trans_type;
    $machineid = $this->machineid;
  	$recycleby = $this->recycleby;
  	$recdate = $this->recdate;
    $recnumber = $this->recnumber;
    $shift = $this->shift;
  	$recstatus = $this->recstatus;
    $remarks = $this->remarks;
  	$postedby = $this->postedby;
  	$productlist = $this->productlist;

    $data = array("machineid"=>$machineid,
                  "recycleby"=>$recycleby,
    	          "recdate"=>$recdate,
                  "recnumber"=>$recnumber,
                  "shift"=>$shift,
                  "recstatus"=>$recstatus,
                  "remarks"=>$remarks,
                  "postedby"=>$postedby,
                  "productlist"=>$productlist);

    if ($trans_type == 'New'){
      $answer = (new ControllerRecycle)->ctrCreateRecycle($data);
    }else{
      $answer = (new ControllerRecycle)->ctrEditRecycle($data);
    }

  }
}

$recycle_materials = new saveRecycle();

$recycle_materials -> trans_type = $_POST["trans_type"];
$recycle_materials -> machineid = $_POST["machineid"];
$recycle_materials -> recycleby = $_POST["recycleby"];
$recycle_materials -> recdate = $_POST["recdate"];
$recycle_materials -> recnumber = $_POST["recnumber"];
$recycle_materials -> shift = $_POST["shift"];
$recycle_materials -> recstatus = $_POST["recstatus"];
$recycle_materials -> remarks = $_POST["remarks"];
$recycle_materials -> postedby = $_POST["postedby"];
$recycle_materials -> productlist = $_POST["productlist"];
$recycle_materials -> postSaveRecycle();