<?php
require_once "../controllers/category.controller.php";
require_once "../models/category.model.php";

class AjaxCategoryRawMats{
    public $idCategory;
    public function ajaxGetCategoryRawMats(){
      $item = "id";
      $value = $this->idCategory;
      $answer = (new ControllerCategory)->ctrShowCategoryRawMats($item, $value);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["idCategory"])){
  $getCategoryRawMats = new AjaxCategoryRawMats();
  $getCategoryRawMats -> idCategory = $_POST["idCategory"];
  $getCategoryRawMats -> ajaxGetCategoryRawMats();
}