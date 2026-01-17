<?php
require_once "../controllers/product.controller.php";
require_once "../models/product.model.php";

class AjaxProductList{ 
   public $categorycode;
   public $brandcode;
   public $accountcode;   
   public $meas1;
   public $vatdesc;

   public function ajaxDisplayProductList(){
     $categorycode = $this->categorycode;
     $brandcode = $this->brandcode;
     $accountcode = $this->accountcode;
     $meas1 = $this->meas1;
     $vatdesc = $this->vatdesc;

     $answer = (new ControllerProduct)->ctrShowProductList($categorycode, $brandcode, $accountcode, $meas1, $vatdesc);
     echo json_encode($answer);
   }
}

$product = new AjaxProductList();
$product -> categorycode = $_POST["categorycode"];
$product -> brandcode = $_POST["brandcode"];
$product -> accountcode = $_POST["accountcode"];
$product -> meas1 = $_POST["meas1"];
$product -> vatdesc = $_POST["vatdesc"];
$product -> ajaxDisplayProductList();