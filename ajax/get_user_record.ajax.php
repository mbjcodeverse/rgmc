<?php
require_once "../controllers/users.controller.php";
require_once "../models/users.model.php";
class AjaxUser{
    public $idUser;
    public function ajaxGetUser(){
      $item = "id";
      $value = $this->idUser;
      $answer = (new ControllerUsers)->ctrShowUsers($item, $value);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["idUser"])){
  $getUser = new AjaxUser();
  $getUser -> idUser = $_POST["idUser"];
  $getUser -> ajaxGetUser();
}