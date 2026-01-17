<?php
class ControllerPurchaseOrder{
	// Save NEW RECORD
	static public function ctrCreatePurchaseOrder($data){
	   	$answer = (new ModelPurchaseOrder)->mdlAddPurchaseOrder($data);
		return $answer;
	}

	// Update EXISTING RECORD
	static public function ctrEditPurchaseOrder($data){
	   	$answer = (new ModelPurchaseOrder)->mdlEditPurchaseOrder($data);
	}

    // Get PO Details
	static public function ctrShowPurchaseOrder($ponumber){
		$answer = (new ModelPurchaseOrder)->mdlShowPurchaseOrder($ponumber);
		return $answer;
	}

    // Get PO Details - For Incoming
	static public function ctrShowPurchaseOrderForIncoming($ponumber){
		$answer = (new ModelPurchaseOrder)->mdlShowPurchaseOrderForIncoming($ponumber);
		return $answer;
	}		

    // Cancel PO
	static public function ctrCancelPurchaseOrder($field, $ponumber){
		$answer = (new ModelPurchaseOrder)->mdlCancelPurchaseOrder($field, $ponumber);
		return $answer;
	}

    // Close PO
	static public function ctrClosePurchaseOrder($field, $ponumber){
		$answer = (new ModelPurchaseOrder)->mdlClosePurchaseOrder($field, $ponumber);
		return $answer;
	}			

    // Get ITEMS
	static public function ctrShowPurchaseOrderItems($ponumber){
		$products = (new ModelPurchaseOrder)->mdlShowPurchaseOrderItems($ponumber);
		return $products;
	}	

    // Purchase | Incoming Qty Comparison
	static public function ctrShowPurchaseIncoming($ponumber){
		$answer = (new ModelPurchaseOrder)->mdlShowPurchaseIncoming($ponumber);
		return $answer;
	}		

    // List TRANSACTIONS
	static public function ctrShowPurchaseOrderTransactionList($branchcode, $start_date, $end_date, $suppliercode, $postatus){
		$answer = (new ModelPurchaseOrder)->mdlShowPurchaseOrderTransactionList($branchcode, $start_date, $end_date, $suppliercode, $postatus);
		return $answer;
	}	

	static public function ctrShowPurchaseReport($machineid, $start_date, $end_date, $categorycode, $suppliercode, $orderedby, $postatus, $reptype){
		$answer = (new ModelPurchaseOrder)->mdlShowPurchaseReport($machineid, $start_date, $end_date, $categorycode, $suppliercode, $orderedby, $postatus, $reptype);
		return $answer;
	}	
}
