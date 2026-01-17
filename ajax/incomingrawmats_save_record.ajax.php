<?php
require_once "../controllers/incomingrawmats.controller.php";
require_once "../models/incomingrawmats.model.php";

class rawmatsOrderList{
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

  public function showRawMatsOrderList(){
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
      $answer = (new ControllerIncomingRawMatsOrder)->ctrCreateIncomingRawMatsOrder($data);
      echo $answer;
    }else{
      $answer = (new ControllerIncomingRawMatsOrder)->ctrEditIncomingRawMatsOrder($data);
    }

  }
}

$processRawMats = new rawmatsOrderList();

$processRawMats -> trans_type = $_POST["trans_type"];
$processRawMats -> suppliercode = $_POST["suppliercode"];
$processRawMats -> podate = $_POST["podate"];
$processRawMats -> postatus = $_POST["postatus"];
$processRawMats -> ponumber = $_POST["ponumber"];
$processRawMats -> orderedby = $_POST["orderedby"];
$processRawMats -> machineid = $_POST["machineid"];
$processRawMats -> preparedby = $_POST["preparedby"];
$processRawMats -> remarks = $_POST["remarks"];
$processRawMats -> amount = $_POST["amount"];
$processRawMats -> discount = $_POST["discount"];
$processRawMats -> netamount = $_POST["netamount"];
$processRawMats -> productlist = $_POST["productlist"];
$processRawMats -> showRawMatsOrderList();