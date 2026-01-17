<?php
require_once "../controllers/incoming.controller.php";
require_once "../models/incoming.model.php";

class incomingList{
  public $trans_type; 

  public $ponumber;
  public $deldate;
  public $delstatus;
  public $inctype;
  public $iscode;
  public $delnumber;
  public $checkedby;
  public $deliveredby;
  public $postedby;
  public $remarks;
  public $amount;
  public $discount;
  public $netamount;
  public $productlist;

  public function showIncomingList(){
    $trans_type = $this->trans_type;

  	$ponumber = $this->ponumber;
  	$deldate = $this->deldate;
  	$delstatus = $this->delstatus;
    $inctype = $this->inctype;
    $iscode = $this->iscode;
  	$delnumber = $this->delnumber;
  	$checkedby = $this->checkedby;
  	$deliveredby = $this->deliveredby;
    $postedby = $this->postedby;
  	$remarks = $this->remarks;
  	$amount = $this->amount;
  	$discount = $this->discount;
  	$netamount = $this->netamount; 
  	$productlist = $this->productlist;

    $data = array("ponumber"=>$ponumber,
    	            "deldate"=>$deldate,
                  "delstatus"=>$delstatus,
                  "inctype"=>$inctype,
                  "iscode"=>$iscode,
                  "delnumber"=>$delnumber,
                  "checkedby"=>$checkedby,
                  "deliveredby"=>$deliveredby,
                  "postedby"=>$postedby,
                  "remarks"=>$remarks,
                  "amount"=>$amount,
                  "discount"=>$discount,
                  "netamount"=>$netamount,
                  "productlist"=>$productlist);

    if ($trans_type == 'New'){
      $answer = (new ControllerIncoming)->ctrCreateIncoming($data);
      echo $answer;
    }else{
      $answer = (new ControllerIncoming)->ctrEditIncoming($data);
    }

  }
}

$processIncoming = new incomingList();

$processIncoming -> trans_type = $_POST["trans_type"];

$processIncoming -> ponumber = $_POST["ponumber"];
$processIncoming -> deldate = $_POST["deldate"];
$processIncoming -> delstatus = $_POST["delstatus"];
$processIncoming -> inctype = $_POST["inctype"];
$processIncoming -> iscode = $_POST["iscode"];
$processIncoming -> delnumber = $_POST["delnumber"];
$processIncoming -> checkedby = $_POST["checkedby"];
$processIncoming -> deliveredby = $_POST["deliveredby"];
$processIncoming -> postedby = $_POST["postedby"];
$processIncoming -> remarks = $_POST["remarks"];
$processIncoming -> amount = $_POST["amount"];
$processIncoming -> discount = $_POST["discount"];
$processIncoming -> netamount = $_POST["netamount"];
$processIncoming -> productlist = $_POST["productlist"];
$processIncoming -> showIncomingList();