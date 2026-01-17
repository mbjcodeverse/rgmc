<?php
require_once "../controllers/items.controller.php";
require_once "../models/items.model.php";

class itemsDetail{
  public $trans_type; 

  public $itemid;
  public $pdesc;
  public $categorycode;
  // public $brandcode;
  public $isactive;
  public $meas1;
  public $eqnum;
  public $meas2;
  public $itemcode;
  public $ucost;
  public $reorder;
  public $purchaseitem;
  public $remarks;

  public function showItemsDetail(){
    $trans_type = $this->trans_type;

  	$itemid = $this->itemid;
  	$pdesc = $this->pdesc;
  	$categorycode = $this->categorycode;
  	// $brandcode = $this->brandcode;
    $isactive = $this->isactive;
    $meas1 = $this->meas1;
    $eqnum = $this->eqnum;
    $meas2 = $this->meas2;
    $itemcode = $this->itemcode;
    $ucost = $this->ucost;
    $reorder = $this->reorder;
    $purchaseitem = $this->purchaseitem;
    $remarks = $this->remarks;

    $data = array("itemid"=>$itemid,
                  "pdesc"=>$pdesc,
                  "categorycode"=>$categorycode,
                  "isactive"=>$isactive,
                  "meas1"=>$meas1,
                  "eqnum"=>$eqnum,
                  "meas2"=>$meas2,
                  "itemcode"=>$itemcode,
                  "ucost"=>$ucost,
                  "reorder"=>$reorder,
                  "purchaseitem"=>$purchaseitem,
                  "remarks"=>$remarks);

    if ($trans_type == 'New'){
      $answer = (new ControllerItems)->ctrCreateItems($data);
    }else{
      $answer = (new ControllerItems)->ctrEditItems($data);
    }

  }
}

$items = new itemsDetail();

$items -> trans_type = $_POST["trans_type"];

$items -> itemid = $_POST["itemid"];
$items -> pdesc = $_POST["pdesc"];
$items -> categorycode = $_POST["categorycode"];
// $items -> brandcode = $_POST["brandcode"];
$items -> isactive = $_POST["isactive"];
$items -> meas1 = $_POST["meas1"];
$items -> eqnum = $_POST["eqnum"];
$items -> meas2 = $_POST["meas2"];
$items -> eqnum2 = $_POST["eqnum2"];
$items -> itemcode = $_POST["itemcode"];
$items -> ucost = $_POST["ucost"];
$items -> reorder = $_POST["reorder"];
$items -> purchaseitem = $_POST["purchaseitem"];
$items -> remarks = $_POST["remarks"];
$items -> showItemsDetail();