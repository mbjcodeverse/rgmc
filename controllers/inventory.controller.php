<?php
class ControllerInventory{
	static public function ctrCreateInventory($data){
	   	$answer = (new ModelInventory)->mdlAddInventory($data);
	}

	static public function ctrEditInventory($data){
		$answer = (new ModelInventory)->mdlEditInventory($data);
    }	

    // List TRANSACTIONS
	static public function ctrShowInventoryTransactionList($start_date, $end_date, $empid, $invstatus){
		$answer = (new ModelInventory)->mdlShowInventoryTransactionList($start_date, $end_date, $empid, $invstatus);
		return $answer;
	}

	static public function ctrShowInventory($invnumber){
		$answer = (new ModelInventory)->mdlShowInventory($invnumber);
		return $answer;
	}

    // Get ITEMS
	static public function ctrShowInventoryItems($invnumber){
		$products = (new ModelInventory)->mdlShowInventoryItems($invnumber);
		return $products;
	}	





	
	static public function ctrShowInventoryTechnical($categorycode, $asofdate){
		$answer = (new ModelInventory)->mdlShowInventoryTechnical($categorycode, $asofdate);
		return $answer;
	}
}