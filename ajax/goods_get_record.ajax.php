<?php
require_once "../controllers/goods.controller.php";
require_once "../models/goods.model.php";

class AjaxGoods{
    public $itemid;
    public function ajaxGetGoods(){
      $item = "itemid";
      $value = $this->itemid;
      $answer = (new ControllerGoods)->ctrShowGood($item, $value);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["itemid"])){
  $getGoods = new AjaxGoods();
  $getGoods -> itemid = $_POST["itemid"];
  $getGoods -> ajaxGetGoods();
}