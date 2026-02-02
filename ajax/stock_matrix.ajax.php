<?php
require_once "../controllers/home.controller.php";
require_once "../models/home.model.php";

class AjaxStockMatrix{ 
   public $inventoryfrom;
   public $inventoryfromnextday;
   public $inventoryto;

   public function ajaxDisplayStockMatrix(){
     $inventoryfrom = $this->inventoryfrom;
     $inventoryfromnextday = $this->inventoryfromnextday;
     $inventoryto = $this->inventoryto;
     $periodic_matrix = (new ControllerHome)->ctrShowStockMatrix($inventoryfrom, $inventoryfromnextday, $inventoryto);
     echo json_encode($periodic_matrix);
   }
}

$inventory_matrix = new AjaxStockMatrix();
$inventory_matrix -> inventoryfrom = $_POST["inventoryfrom"];
$inventory_matrix -> inventoryfromnextday = $_POST["inventoryfromnextday"];
$inventory_matrix -> inventoryto = $_POST["inventoryto"];
$inventory_matrix -> ajaxDisplayStockMatrix();