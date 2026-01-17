<?php
require_once "../controllers/building.controller.php";
require_once "../models/building.model.php";

class AjaxBuilding{
    public $idBuilding;
    public function ajaxGetBuilding(){
      $item = "id";
      $value = $this->idBuilding;
      $answer = (new ControllerBuilding)->ctrShowBuilding($item, $value);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["idBuilding"])){
  $getBuilding = new AjaxBuilding();
  $getBuilding -> idBuilding = $_POST["idBuilding"];
  $getBuilding -> ajaxGetBuilding();
}