<?php
require_once "../controllers/classification.controller.php";
require_once "../models/classification.model.php";

class AjaxClassification{
    public $idClass;
    public function ajaxGetClassification(){
      $item = "id";
      $value = $this->idClass;
      $answer = (new ControllerClassification)->ctrShowClassification($item, $value);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["idClass"])){
  $getClassification = new AjaxClassification();
  $getClassification -> idClass = $_POST["idClass"];
  $getClassification -> ajaxGetClassification();
}