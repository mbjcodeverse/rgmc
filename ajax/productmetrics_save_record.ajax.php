<?php
require_once "../controllers/prodmetrics.controller.php";
require_once "../models/prodmetrics.model.php";

class saveProductmetrics{
  public $trans_type; 
  public $categorycode;
  public $prodmetid;
  public $headcount;
  public $dailyrate;
  public $amount;
  public $mdate;
  public $postedby;

  public function postSaveProductmetrics(){
    $trans_type = $this->trans_type;
    $categorycode = $this->categorycode;
    $prodmetid = $this->prodmetid;
  	$headcount = $this->headcount;
    $dailyrate = $this->dailyrate;
    $amount = $this->amount;
    $mdate = $this->mdate;
  	$postedby = $this->postedby;

    $data = array("categorycode"=>$categorycode,
                  "prodmetid"=>$prodmetid,
    	          "headcount"=>$headcount,
                  "dailyrate"=>$dailyrate,
                  "amount"=>$amount,
                  "mdate"=>$mdate,
                  "postedby"=>$postedby);

    if ($trans_type == 'New'){
      $answer = (new ControllerProductmetrics)->ctrCreateProductmetrics($data);
    }else{
      $answer = (new ControllerProductmetrics)->ctrEditProductmetrics($data);
    }

  }
}

$product_metrics = new saveProductmetrics();

$product_metrics -> trans_type = $_POST["trans_type"];
$product_metrics -> categorycode = $_POST["categorycode"];
$product_metrics -> prodmetid = $_POST["prodmetid"];
$product_metrics -> headcount = $_POST["headcount"];
$product_metrics -> dailyrate = $_POST["dailyrate"];
$product_metrics -> amount = $_POST["amount"];
$product_metrics -> mdate = $_POST["mdate"];
$product_metrics -> postedby = $_POST["postedby"];
$product_metrics -> postSaveProductmetrics();