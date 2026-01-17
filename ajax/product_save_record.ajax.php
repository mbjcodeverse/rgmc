<?php
require_once "../controllers/product.controller.php";
require_once "../models/product.model.php";

class productDetail{
  public $trans_type; 

  public $prodid;
  public $prodclass;
  public $pdesc;
  public $dimension;
  public $size;
  public $pcolor;
  public $categorycode;
  public $brandcode;
  public $isactive;
  public $meas1;
  public $eqnum;
  public $meas2;
  public $eqnum2;
  public $meas3; 
  public $purchaseitem;
  public $accountcode;
  public $vatdesc;
  public $remarks; 
  public $prodname;

  public function showProductDetail(){
    $trans_type = $this->trans_type;

  	$prodid = $this->prodid;
  	$prodclass = $this->prodclass;
  	$pdesc = $this->pdesc;
    $dimension = $this->dimension;
  	$size = $this->size;
  	$pcolor = $this->pcolor;
  	$categorycode = $this->categorycode;
  	$brandcode = $this->brandcode;
    $isactive = $this->isactive;
    $meas1 = $this->meas1;
    $eqnum = $this->eqnum;
    $meas2 = $this->meas2;
    $eqnum2 = $this->eqnum2;
    $meas3 = $this->meas3;
    $abbr = $this->abbr;
    $barcode = $this->barcode;
    $ucost = $this->ucost;
    $markup = $this->markup;
    $uprice = $this->uprice;
    $acost = $this->acost;
    $reorder = $this->reorder;
    $purchaseitem = $this->purchaseitem;
    $accountcode = $this->accountcode;
    $vatdesc = $this->vatdesc;
    $remarks = $this->remarks;
    $prodname = $this->prodname;

    $data = array("prodid"=>$prodid,
    	            "prodclass"=>$prodclass,
                  "pdesc"=>$pdesc,
                  "dimension"=>$dimension,
                  "size"=>$size,
                  "pcolor"=>$pcolor,
                  "categorycode"=>$categorycode,
                  "brandcode"=>$brandcode,
                  "isactive"=>$isactive,
                  "meas1"=>$meas1,
                  "eqnum"=>$eqnum,
                  "meas2"=>$meas2,
                  "eqnum2"=>$eqnum2,
                  "meas3"=>$meas3,
                  "abbr"=>$abbr,
                  "barcode"=>$barcode,
                  "ucost"=>$ucost,
                  "markup"=>$markup,
                  "uprice"=>$uprice,
                  "acost"=>$acost,
                  "reorder"=>$reorder,
                  "purchaseitem"=>$purchaseitem,
                  "accountcode"=>$accountcode,
                  "vatdesc"=>$vatdesc,
                  "remarks"=>$remarks,
                  "prodname"=>$prodname);

    if ($trans_type == 'New'){
      $answer = (new ControllerProduct)->ctrCreateProduct($data);
    }else{
      $answer = (new ControllerProduct)->ctrEditProduct($data);
    }

  }
}

$product = new productDetail();

$product -> trans_type = $_POST["trans_type"];

$product -> prodid = $_POST["prodid"];
$product -> prodclass = $_POST["prodclass"];
$product -> pdesc = $_POST["pdesc"];
$product -> dimension = $_POST["dimension"];
$product -> size = $_POST["size"];
$product -> pcolor = $_POST["pcolor"];
$product -> categorycode = $_POST["categorycode"];
$product -> brandcode = $_POST["brandcode"];
$product -> isactive = $_POST["isactive"];
$product -> meas1 = $_POST["meas1"];
$product -> eqnum = $_POST["eqnum"];
$product -> meas2 = $_POST["meas2"];
$product -> eqnum2 = $_POST["eqnum2"];
$product -> meas3 = $_POST["meas3"];
$product -> abbr = $_POST["abbr"];
$product -> barcode = $_POST["barcode"];
$product -> ucost = $_POST["ucost"];
$product -> markup = $_POST["markup"];
$product -> uprice = $_POST["uprice"];
$product -> acost = $_POST["acost"];
$product -> reorder = $_POST["reorder"];
$product -> purchaseitem = $_POST["purchaseitem"];
$product -> accountcode = $_POST["accountcode"];
$product -> vatdesc = $_POST["vatdesc"];
$product -> remarks = $_POST["remarks"];
$product -> prodname = $_POST["prodname"];
$product -> showProductDetail();