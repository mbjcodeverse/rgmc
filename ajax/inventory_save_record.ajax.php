<?php
require_once "../controllers/inventory.controller.php";
require_once "../models/inventory.model.php";

class saveInventory{
  public $trans_type; 
  public $countedby;
  public $invdate;
  public $invnumber;
  public $invstatus;
  public $remarks;
  public $postedby;
  public $productlist;

  public function postSavedInventory(){
    $trans_type = $this->trans_type;
  	$countedby = $this->countedby;
  	$invdate = $this->invdate;
    $invnumber = $this->invnumber;
  	$invstatus = $this->invstatus;
    $remarks = $this->remarks;
  	$postedby = $this->postedby;
  	$productlist = $this->productlist;

    $data = array("countedby"=>$countedby,
    	            "invdate"=>$invdate,
                  "invnumber"=>$invnumber,
                  "invstatus"=>$invstatus,
                  "remarks"=>$remarks,
                  "postedby"=>$postedby,
                  "productlist"=>$productlist);

    if ($trans_type == 'New'){
      $answer = (new ControllerInventory)->ctrCreateInventory($data);
      echo $answer;
    }else{
      $answer = (new ControllerInventory)->ctrEditInventory($data);
    }

  }
}

$processPO = new saveInventory();

$processPO -> trans_type = $_POST["trans_type"];
$processPO -> countedby = $_POST["countedby"];
$processPO -> invdate = $_POST["invdate"];
$processPO -> invnumber = $_POST["invnumber"];
$processPO -> invstatus = $_POST["invstatus"];
$processPO -> remarks = $_POST["remarks"];
$processPO -> postedby = $_POST["postedby"];
$processPO -> productlist = $_POST["productlist"];
$processPO -> postSavedInventory();