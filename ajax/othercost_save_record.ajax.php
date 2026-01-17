<?php
require_once "../controllers/othercost.controller.php";
require_once "../models/othercost.model.php";

class saveOthercost{
  public $trans_type; 
  public $ocostid;
  public $electricity;
  public $manpower;
  public $sales;
  public $odate;
  public $postedby;

  public function postSaveOthercost(){
    $trans_type = $this->trans_type;
    $ocostid = $this->ocostid;
  	$electricity = $this->electricity;
  	$manpower = $this->manpower;
    $sales = $this->sales;
    $odate = $this->odate;
  	$postedby = $this->postedby;

    $data = array("ocostid"=>$ocostid,
                  "electricity"=>$electricity,
    	            "manpower"=>$manpower,
                  "sales"=>$sales,
                  "odate"=>$odate,
                  "postedby"=>$postedby);

    if ($trans_type == 'New'){
      $answer = (new ControllerOthercost)->ctrCreateOthercost($data);
    }else{
      $answer = (new ControllerOthercost)->ctrEditOthercost($data);
    }

  }
}

$other_cost = new saveOthercost();

$other_cost -> trans_type = $_POST["trans_type"];
$other_cost -> ocostid = $_POST["ocostid"];
$other_cost -> electricity = $_POST["electricity"];
$other_cost -> manpower = $_POST["manpower"];
$other_cost -> sales = $_POST["sales"];
$other_cost -> odate = $_POST["odate"];
$other_cost -> postedby = $_POST["postedby"];
$other_cost -> postSaveOthercost();