<?php
require_once "../controllers/items.controller.php";
require_once "../models/items.model.php";

class AllItems{
	public function showAllItems(){
		$items = (new ControllerItems)->ctrShowAllItems();
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
					$itemid = $items[$i]["itemid"];
					$jsonData .='[
						"'.$pdesc.'",
						"'.number_format($items[$i]["ucost"]/$items[$i]["eqnum"], 2, '.', ',').'",
						"'.$itemid.'",
						"'.$buttons.'"
					],';
				}
				$jsonData = substr($jsonData, 0, -1);
				$jsonData .= '] 
			}';
		echo $jsonData;
	}
}

$listItems = new AllItems();
$listItems -> showAllItems();