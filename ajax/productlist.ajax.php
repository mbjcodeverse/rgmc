<?php
require_once "../controllers/product.controller.php";
require_once "../models/product.model.php";

class ProductList{
	public function showProductList(){
		$products = (new ControllerProduct)->ctrShowProductList();
		if(count($products) == 0){
			$jsonData = '{"data":[]}';
			echo $jsonData;
			return;
		}
		$jsonData = '{
			"data":[';
				for($i=0; $i < count($products); $i++){
	                $buttons = "<button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct recoverButton' idProduct='".$products[$i]["id"]."'><i class='icon-check'></i></button>";

					$jsonData .='[
						"'.$products[$i]["pdesc"].'",
						"'.$buttons.'"
					],';
				}
				$jsonData = substr($jsonData, 0, -1);
				$jsonData .= '] 
			}';
		echo $jsonData;
	}
}

$activateProducts = new ProductList();
$activateProducts -> showProductList();

					// $jsonData .='[
					// 	"'.$products[$i]["pdesc"].'",
					// 	"'.$products[$i]["mdesc1"].'",
					// 	"'.$buttons.'"
					// ],';