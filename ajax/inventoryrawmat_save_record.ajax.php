<?php
require_once "../controllers/inventoryrawmats.controller.php";
require_once "../models/inventoryrawmats.model.php";

class saveInventoryRawmats{
  public $trans_type; 
  public $countedby;
  public $invdate;
  public $invnumber;
  public $invstatus;
  public $remarks;
  public $postedby;
  public $productlist;

  public function postSavedInventoryRawmats(){
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
      $answer = (new ControllerInventoryRawmats)->ctrCreateInventoryRawmats($data);
      echo $answer;
    }else{
      $answer = (new ControllerInventoryRawmats)->ctrEditInventoryRawmats($data);
    }

  }
}

$inventory_rawmats = new saveInventoryRawmats();

$inventory_rawmats -> trans_type = $_POST["trans_type"];
$inventory_rawmats -> countedby = $_POST["countedby"];
$inventory_rawmats -> invdate = $_POST["invdate"];
$inventory_rawmats -> invnumber = $_POST["invnumber"];
$inventory_rawmats -> invstatus = $_POST["invstatus"];
$inventory_rawmats -> remarks = $_POST["remarks"];
$inventory_rawmats -> postedby = $_POST["postedby"];
$inventory_rawmats -> productlist = $_POST["productlist"];
$inventory_rawmats -> postSavedInventoryRawmats();