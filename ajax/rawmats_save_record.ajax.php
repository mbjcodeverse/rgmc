<?php
require_once "../controllers/rawmats.controller.php";
require_once "../models/rawmats.model.php";

class rawmatsDetail{
  public $trans_type; 

  public $itemid;
  public $pdesc;
  public $categorycode;
  public $isactive;
  public $meas2;
  public $itemcode;
  public $ucost;
  public $purchaseitem;
  public $remarks;
  public $classification;
  public $critical;
  public $low;
  public $moderate;
  public $high;

  public function showRawMatsDetail(){
    $trans_type = $this->trans_type;

  	$itemid = $this->itemid;
  	$pdesc = $this->pdesc;
  	$categorycode = $this->categorycode;
    $isactive = $this->isactive;
    $meas2 = $this->meas2;
    $itemcode = $this->itemcode;
    $ucost = $this->ucost;
    $purchaseitem = $this->purchaseitem;
    $remarks = $this->remarks;
    $classification = $this->classification;
    $critical = $this->critical;
    $low = $this->low;
    $moderate = $this->moderate;
    $high = $this->high;

    $data = array("itemid"=>$itemid,
                  "pdesc"=>$pdesc,
                  "categorycode"=>$categorycode,
                  "isactive"=>$isactive,
                  "meas2"=>$meas2,
                  "itemcode"=>$itemcode,
                  "ucost"=>$ucost,
                  "purchaseitem"=>$purchaseitem,
                  "remarks"=>$remarks,
                  "classification"=>$classification,
                  "critical"=>$critical,
                  "low"=>$low,
                  "moderate"=>$moderate,
                  "high"=>$high);

    if ($trans_type == 'New'){
      $answer = (new ControllerRawMats)->ctrCreateRawMats($data);
    }else{
      $answer = (new ControllerRawMats)->ctrEditRawMats($data);
    }

  }
}

$items = new rawmatsDetail();

$items -> trans_type = $_POST["trans_type"];

$items -> itemid = $_POST["itemid"];
$items -> pdesc = $_POST["pdesc"];
$items -> categorycode = $_POST["categorycode"];
$items -> isactive = $_POST["isactive"];
$items -> meas2 = $_POST["meas2"];
$items -> itemcode = $_POST["itemcode"];
$items -> ucost = $_POST["ucost"];
$items -> purchaseitem = $_POST["purchaseitem"];
$items -> remarks = $_POST["remarks"];
$items -> classification = $_POST["classification"];
$items -> critical = $_POST["critical"];
$items -> low = $_POST["low"];
$items -> moderate = $_POST["moderate"];
$items -> high = $_POST["high"];
$items -> showRawMatsDetail();