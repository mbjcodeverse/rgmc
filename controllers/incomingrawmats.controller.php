<?php
class ControllerIncomingRawMatsOrder{
	// Save NEW RECORD
	static public function ctrCreateIncomingRawMatsOrder($data){
	   	$answer = (new ModelIncomingRawMatsOrder)->mdlAddIncomingRawMatsOrder($data);
		return $answer;
	}

	// Update EXISTING RECORD
	static public function ctrEditIncomingRawMatsOrder($data){
	   	$answer = (new ModelIncomingRawMatsOrder)->mdlEditIncomingRawMatsOrder($data);
	}

    // Get PO Details
	static public function ctrShowIncomingRawMatsOrder($ponumber){
		$answer = (new ModelIncomingRawMatsOrder)->mdlShowIncomingRawMatsOrder($ponumber);
		return $answer;
	}

    // Get PO Details - For Incoming
	static public function ctrShowIncomingRawMatsOrderForIncoming($ponumber){
		$answer = (new ModelIncomingRawMatsOrder)->mdlShowIncomingRawMatsOrderForIncoming($ponumber);
		return $answer;
	}		

    // Cancel PO
	static public function ctrCancelIncomingRawMatsOrder($field, $ponumber){
		$answer = (new ModelIncomingRawMatsOrder)->mdlCancelIncomingRawMatsOrder($field, $ponumber);
		return $answer;
	}

    // Close PO
	static public function ctrCloseIncomingRawMatsOrder($field, $ponumber){
		$answer = (new ModelIncomingRawMatsOrder)->mdlCloseIncomingRawMatsOrder($field, $ponumber);
		return $answer;
	}			

    // Get ITEMS
	static public function ctrShowIncomingRawMatsOrderItems($ponumber){
		$products = (new ModelIncomingRawMatsOrder)->mdlShowIncomingRawMatsOrderItems($ponumber);
		return $products;
	}	

    // IncomingRawMats | Incoming Qty Comparison
	static public function ctrShowIncomingRawMatsIncoming($ponumber){
		$answer = (new ModelIncomingRawMatsOrder)->mdlShowIncomingRawMatsIncoming($ponumber);
		return $answer;
	}		

    // List TRANSACTIONS
	static public function ctrShowIncomingRawMatsOrderTransactionList($branchcode, $start_date, $end_date, $suppliercode, $postatus){
		$answer = (new ModelIncomingRawMatsOrder)->mdlShowIncomingRawMatsOrderTransactionList($branchcode, $start_date, $end_date, $suppliercode, $postatus);
		return $answer;
	}	

	static public function ctrShowIncomingRawMatsReport($machineid, $start_date, $end_date, $categorycode, $suppliercode, $orderedby, $postatus, $reptype){
		$answer = (new ModelIncomingRawMatsOrder)->mdlShowIncomingRawMatsReport($machineid, $start_date, $end_date, $categorycode, $suppliercode, $orderedby, $postatus, $reptype);
		return $answer;
	}	

	    // Get PO Details
	static public function ctrCancelPurchaseOrder($ponumber){
		$answer = (new ModelIncomingRawMatsOrder)->mdlCancelPurchaseOrder($ponumber);
		return $answer;
	}
}
