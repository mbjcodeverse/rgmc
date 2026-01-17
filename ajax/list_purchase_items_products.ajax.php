<?php
require_once "../controllers/items.controller.php";
require_once "../models/items.model.php";

class PurchaseItemProducts{
	public function showPurchaseItemProducts(){
		$items = (new ControllerItems)->ctrShowPurchaseItemProducts();
		if(count($items) == 0){
			$jsonData = '{"data":[]}';
			echo $jsonData;
			return;
		}
		$jsonData = '{
			"data":[';
				for($i=0; $i < count($items); $i++){
	                $buttons = "<button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct recoverButton' idProduct='".$items[$i]["id"]."' itemid='".$items[$i]["itemid"]."'><i class='icon-check'></i></button>";
					$pdesc = $items[$i]["pdesc"].' '.$items[$i]["itemcode"].' ('.strtoupper($items[$i]["meas1"]).')';

					$jsonData .='[
						"'.$pdesc.'",
						"'.number_format($items[$i]["ucost"], 2, '.', ',').'",
						"'.$buttons.'"
					],';
				}
				$jsonData = substr($jsonData, 0, -1);
				$jsonData .= '] 
			}';
		echo $jsonData;
	}
}

$listItems = new PurchaseItemProducts();
$listItems -> showPurchaseItemProducts();