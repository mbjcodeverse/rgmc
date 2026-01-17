<?php
require_once "../controllers/goods.controller.php";
require_once "../models/goods.model.php";

class AjaxGoodsList{ 
   public $categorycode;

   public function ajaxDisplayGoodsList(){
     $categorycode = $this->categorycode;

     $answer = (new ControllerGoods)->ctrShowGoodsList($categorycode);
     echo json_encode($answer);
   }
}

$goods = new AjaxGoodsList();
$goods -> categorycode = $_POST["categorycode"];
$goods -> ajaxDisplayGoodsList();