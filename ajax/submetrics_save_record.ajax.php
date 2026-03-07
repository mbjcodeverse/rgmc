<?php
require_once "../controllers/submetrics.controller.php";
require_once "../models/submetrics.model.php";

class saveSubmetrics{
  public $trans_type; 
  public $categorycode;
  public $submetid;
  public $headcount;
  public $dailyrate;
  public $amount;
  public $mdate;
  public $postedby;

  public function postSaveSubmetrics(){
    $trans_type = $this->trans_type;
    $categorycode = $this->categorycode;
    $submetid = $this->submetid;
  	$headcount = $this->headcount;
    $dailyrate = $this->dailyrate;
    $amount = $this->amount;
    $mdate = $this->mdate;
  	$postedby = $this->postedby;

    $data = array("categorycode"=>$categorycode,
                  "submetid"=>$submetid,
    	          "headcount"=>$headcount,
                  "dailyrate"=>$dailyrate,
                  "amount"=>$amount,
                  "mdate"=>$mdate,
                  "postedby"=>$postedby);

    if ($trans_type == 'New'){
      $answer = (new ControllerSubmetrics)->ctrCreateSubmetrics($data);
    }else{
      $answer = (new ControllerSubmetrics)->ctrEditSubmetrics($data);
    }

  }
}

$sub_metrics = new saveSubmetrics();

$sub_metrics -> trans_type = $_POST["trans_type"];
$sub_metrics -> categorycode = $_POST["categorycode"];
$sub_metrics -> submetid = $_POST["submetid"];
$sub_metrics -> headcount = $_POST["headcount"];
$sub_metrics -> dailyrate = $_POST["dailyrate"];
$sub_metrics -> amount = $_POST["amount"];
$sub_metrics -> mdate = $_POST["mdate"];
$sub_metrics -> postedby = $_POST["postedby"];
$sub_metrics -> postSaveSubmetrics();