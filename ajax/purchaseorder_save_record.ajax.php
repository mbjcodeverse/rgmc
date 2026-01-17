<?php
require_once "../controllers/purchaseorder.controller.php";
require_once "../models/purchaseorder.model.php";

class purchaseorderList{
  public $trans_type; 
  public $suppliercode;
  public $podate;
  public $postatus;
  public $ponumber;
  public $orderedby;
  public $machineid;
  public $preparedby;
  public $remarks;
  public $amount;
  public $discount;
  public $netamount;
  public $productlist;

  public function showPurchaseOrderList(){
    $trans_type = $this->trans_type;
  	$suppliercode = $this->suppliercode;
  	$podate = $this->podate;
  	$postatus = $this->postatus;
    $ponumber = $this->ponumber;
  	$orderedby = $this->orderedby;
  	$machineid = $this->machineid;
  	$preparedby = $this->preparedby;
  	$remarks = $this->remarks;
  	$amount = $this->amount;
  	$discount = $this->discount;
  	$netamount = $this->netamount; 
  	$productlist = $this->productlist;

    $data = array("suppliercode"=>$suppliercode,
    	            "podate"=>$podate,
                  "postatus"=>$postatus,
                  "ponumber"=>$ponumber,
                  "orderedby"=>$orderedby,
                  "machineid"=>$machineid,
                  "preparedby"=>$preparedby,
                  "remarks"=>$remarks,
                  "amount"=>$amount,
                  "discount"=>$discount,
                  "netamount"=>$netamount,
                  "productlist"=>$productlist);

    if ($trans_type == 'New'){
      $answer = (new ControllerPurchaseOrder)->ctrCreatePurchaseOrder($data);
      echo $answer;
    }else{
      $answer = (new ControllerPurchaseOrder)->ctrEditPurchaseOrder($data);
    }

  }
}

$processPO = new purchaseorderList();

$processPO -> trans_type = $_POST["trans_type"];
$processPO -> suppliercode = $_POST["suppliercode"];
$processPO -> podate = $_POST["podate"];
$processPO -> postatus = $_POST["postatus"];
$processPO -> ponumber = $_POST["ponumber"];
$processPO -> orderedby = $_POST["orderedby"];
$processPO -> machineid = $_POST["machineid"];
$processPO -> preparedby = $_POST["preparedby"];
$processPO -> remarks = $_POST["remarks"];
$processPO -> amount = $_POST["amount"];
$processPO -> discount = $_POST["discount"];
$processPO -> netamount = $_POST["netamount"];
$processPO -> productlist = $_POST["productlist"];
$processPO -> showPurchaseOrderList();