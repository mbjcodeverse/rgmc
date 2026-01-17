<?php
class ControllerInventoryRawmats{
	static public function ctrCreateInventoryRawmats($data){
	   	$answer = (new ModelInventoryRawmats)->mdlAddInventoryRawmats($data);
	}

	static public function ctrEditInventoryRawmats($data){
		$answer = (new ModelInventoryRawmats)->mdlEditInventoryRawmats($data);
    }	

    // List TRANSACTIONS
	static public function ctrShowInventoryRawmatsTransactionList($start_date, $end_date, $empid, $invstatus){
		$answer = (new ModelInventoryRawmats)->mdlShowInventoryRawmatsTransactionList($start_date, $end_date, $empid, $invstatus);
		return $answer;
	}

	static public function ctrShowInventoryRawmats($invnumber){
		$answer = (new ModelInventoryRawmats)->mdlShowInventoryRawmats($invnumber);
		return $answer;
	}

    // Get ITEMS
	static public function ctrShowInventoryRawmatsItems($invnumber){
		$products = (new ModelInventoryRawmats)->mdlShowInventoryRawmatsItems($invnumber);
		return $products;
	}	
}