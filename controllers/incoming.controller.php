<?php
class ControllerIncoming{
	// Save NEW RECORD
	static public function ctrCreateIncoming($data){
	   	$answer = (new ModelIncoming)->mdlAddIncoming($data);
		return $answer;
	}

	// Update EXISTING RECORD
	static public function ctrEditIncoming($data){
	   	$answer = (new ModelIncoming)->mdlEditIncoming($data);
	}

	// Incoming Report
	static public function ctrShowIncomingReport($machineid, $start_date, $end_date, $categorycode, $suppliercode, $delstatus, $reptype){
		$answer = (new ModelIncoming)->mdlShowIncomingReport($machineid, $start_date, $end_date, $categorycode, $suppliercode, $delstatus, $reptype);
		return $answer;
	}

    // Get Incoming Details
	static public function ctrShowIncoming($delnumber){
		$answer = (new ModelIncoming)->mdlShowIncoming($delnumber);
		return $answer;
	}		

    // Cancel Incoming
	static public function ctrCancelIncoming($ponumber, $delnumber){
		$answer = (new ModelIncoming)->mdlCancelIncoming($ponumber, $delnumber);
		return $answer;
	}			

    // Get ITEMS
	static public function ctrShowIncomingItems($delnumber){
		$products = (new ModelIncoming)->mdlShowIncomingItems($delnumber);
		return $products;
	}	

    // List TRANSACTIONS
	static public function ctrShowIncomingTransactionList($machineid, $start_date, $end_date, $suppliercode, $delstatus){
		$answer = (new ModelIncoming)->mdlShowIncomingTransactionList($machineid, $start_date, $end_date, $suppliercode, $delstatus);
		return $answer;
	}	
}
