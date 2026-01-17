<?php
require_once "../controllers/debris.controller.php";
require_once "../models/debris.model.php";

class saveDebris{
  public $trans_type; 
  public $machineid;
  public $debrisby;
  public $debdate;
  public $debnumber;
  public $shift;
  public $debstatus;
  public $remarks;
  public $postedby;
  public $productlist;

  public function postSaveDebris(){
    $trans_type = $this->trans_type;
    $machineid = $this->machineid;
  	$debrisby = $this->debrisby;
  	$debdate = $this->debdate;
    $debnumber = $this->debnumber;
    $shift = $this->shift;
  	$debstatus = $this->debstatus;
    $remarks = $this->remarks;
  	$postedby = $this->postedby;
  	$productlist = $this->productlist;

    $data = array("machineid"=>$machineid,
                  "debrisby"=>$debrisby,
    	          "debdate"=>$debdate,
                  "debnumber"=>$debnumber,
                  "shift"=>$shift,
                  "debstatus"=>$debstatus,
                  "remarks"=>$remarks,
                  "postedby"=>$postedby,
                  "productlist"=>$productlist);

    if ($trans_type == 'New'){
      $answer = (new ControllerDebris)->ctrCreateDebris($data);
    }else{
      $answer = (new ControllerDebris)->ctrEditDebris($data);
    }

  }
}

$debris_materials = new saveDebris();

$debris_materials -> trans_type = $_POST["trans_type"];
$debris_materials -> machineid = $_POST["machineid"];
$debris_materials -> debrisby = $_POST["debrisby"];
$debris_materials -> debdate = $_POST["debdate"];
$debris_materials -> debnumber = $_POST["debnumber"];
$debris_materials -> shift = $_POST["shift"];
$debris_materials -> debstatus = $_POST["debstatus"];
$debris_materials -> remarks = $_POST["remarks"];
$debris_materials -> postedby = $_POST["postedby"];
$debris_materials -> productlist = $_POST["productlist"];
$debris_materials -> postSaveDebris();