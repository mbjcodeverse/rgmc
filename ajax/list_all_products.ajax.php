<?php
require_once "../controllers/goods.controller.php";
require_once "../models/goods.model.php";

class GoodsItemProducts{
	public function showGoodProducts(){
		$items = (new ControllerGoods)->ctrShowPurchaseGoodProducts();
		if(count($items) == 0){
			$jsonData = '{"data":[]}';
			echo $jsonData;
			return;
		}
		$jsonData = '{
			"data":[';
				for($i=0; $i < count($items); $i++){
	                $buttons = "<button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct recoverButton' idProduct='".$items[$i]["id"]."' itemid='".$items[$i]["itemid"]."'><i class='icon-check'></i></button>";
					$pdesc = $items[$i]["pdesc"].' ('.strtoupper($items[$i]["meas2"]).')';
					$itemid = $items[$i]["itemcode"];
					$jsonData .='[
						"'.$pdesc.'",
						"'.$itemid.'",
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

$listProducts = new GoodsItemProducts();
$listProducts -> showGoodProducts();