<?php
require_once "../controllers/prodfin.controller.php";
require_once "../models/prodfin.model.php";

class saveProdfin{
  public $trans_type; 
  public $machineid;
  public $operatedby;
  public $proddate;
  public $prodnumber;
  public $shift;
  public $prodstatus;
  public $remarks;
  public $postedby;
  public $productlist;
  public $debnumber;
  public $debstatus;
  public $wremarks;
  public $wastelist;

  public function postSaveProdfin(){
    $trans_type = $this->trans_type;
    $machineid = $this->machineid;
  	$operatedby = $this->operatedby;
  	$proddate = $this->proddate;
    $prodnumber = $this->prodnumber;
    $shift = $this->shift;
  	$prodstatus = $this->prodstatus;
    $remarks = $this->remarks;
  	$postedby = $this->postedby;
  	$productlist = $this->productlist;

    $debnumber = $this->debnumber;
    $debstatus = $this->debstatus;
    $wremarks = $this->wremarks;
    $wastelist = $this->wastelist;

    $data = array("machineid"=>$machineid,
                  "operatedby"=>$operatedby,
    	            "proddate"=>$proddate,
                  "prodnumber"=>$prodnumber,
                  "shift"=>$shift,
                  "prodstatus"=>$prodstatus,
                  "remarks"=>$remarks,
                  "postedby"=>$postedby,
                  "productlist"=>$productlist,
                  "debnumber"=>$debnumber,
                  "debstatus"=>$debstatus,
                  "wremarks"=>$wremarks,
                  "wastelist"=>$wastelist);

    if ($trans_type == 'New'){
      $answer = (new ControllerProdfin)->ctrCreateProdfin($data);
    }else{
      $answer = (new ControllerProdfin)->ctrEditProdfin($data);
    }

  }
}

$prod_fin = new saveProdfin();

$prod_fin -> trans_type = $_POST["trans_type"];
$prod_fin -> machineid = $_POST["machineid"];
$prod_fin -> operatedby = $_POST["operatedby"];
$prod_fin -> proddate = $_POST["proddate"];
$prod_fin -> prodnumber = $_POST["prodnumber"];
$prod_fin -> shift = $_POST["shift"];
$prod_fin -> prodstatus = $_POST["prodstatus"];
$prod_fin -> remarks = $_POST["remarks"];
$prod_fin -> postedby = $_POST["postedby"];
$prod_fin -> productlist = $_POST["productlist"];

$prod_fin -> debnumber = $_POST["debnumber"];
$prod_fin -> debstatus = $_POST["debstatus"];
$prod_fin -> wremarks = $_POST["wremarks"];
$prod_fin -> wastelist = $_POST["wastelist"];

$prod_fin -> postSaveProdfin();