<?php
require_once "../controllers/category.controller.php";
require_once "../models/category.model.php";

class AjaxCategoryGoods{
    public $idCategory;
    public function ajaxGetCategoryGoods(){
      $item = "id";
      $value = $this->idCategory;
      $answer = (new ControllerCategory)->ctrShowCategoryGoods($item, $value);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["idCategory"])){
  $getCategoryGoods = new AjaxCategoryGoods();
  $getCategoryGoods -> idCategory = $_POST["idCategory"];
  $getCategoryGoods -> ajaxGetCategoryGoods();
}