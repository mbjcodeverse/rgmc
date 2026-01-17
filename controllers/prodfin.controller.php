<?php
class ControllerProdfin{
	static public function ctrCreateProdfin($data){
	   	$answer = (new ModelProdfin)->mdlAddProdfin($data);
	}

	static public function ctrEditProdfin($data){
		$answer = (new ModelProdfin)->mdlEditProdfin($data);
    }
	
	// OPERATOR Production
	static public function ctrCreateProdoperator($data){
	   	$answer = (new ModelProdfin)->mdlCreateProdoperator($data);
	}

	static public function ctrEditProdoperator($data){
	   	$answer = (new ModelProdfin)->mdlEditProdoperator($data);
	}	

    // List TRANSACTIONS
	static public function ctrShowProdfinTransactionList($machineid, $start_date, $end_date, $empid, $prodstatus){
		$answer = (new ModelProdfin)->mdlShowProdfinTransactionList($machineid, $start_date, $end_date, $empid, $prodstatus);
		return $answer;
	}	

	// List TRANSACTIONS (FOR PRACTICE)
	static public function ctrShowProdoperatorTransactionList($machineid, $start_date, $end_date, $empid, $prodstatus, $etype, $postedby){
		$answer = (new ModelProdfin)->mdlShowProdoperatorTransactionList($machineid, $start_date, $end_date, $empid, $prodstatus, $etype, $postedby);
		return $answer;
	}	

	static public function ctrShowProdfinReport($machineid, $start_date, $end_date, $categorycode, $postedby, $operatedby, $prodstatus, $reptype, $shift){
		$answer = (new ModelProdfin)->mdlShowProdfinReport($machineid, $start_date, $end_date, $categorycode, $postedby, $operatedby, $prodstatus, $reptype, $shift);
		return $answer;
	}

	// Get Prodfin Details
	static public function ctrShowProdfin($prodnumber){
		$answer = (new ModelProdfin)->mdlShowProdfin($prodnumber);
		return $answer;
	}

	// Get Operator Production
	static public function ctrShowProdoperator($prodnumber,$etype){
		$answer = (new ModelProdfin)->mdlShowProdoperator($prodnumber,$etype);
		return $answer;
	}

	// Get Operator Production Items
	static public function ctrShowProdoperatorItems($prodnumber,$etype){
		$products = (new ModelProdfin)->mdlShowProdoperatorItems($prodnumber,$etype);
		return $products;
	}

    // Get ITEMS
	static public function ctrShowProdfinItems($prodnumber){
		$products = (new ModelProdfin)->mdlShowProdfinItems($prodnumber);
		return $products;
	}

	static public function ctrCancelProdfin($prodnumber){
		$answer = (new ModelProdfin)->mdlCancelProdfin($prodnumber);
		return $answer;
	}
	
	// ---------- Machine Production Capacity -----------
	static public function ctrCreateProdcapacity($data){
	   	$answer = (new ModelProdfin)->mdlAddProdcapacity($data);
	}

	static public function ctrEditProdcapacity($data){
		$answer = (new ModelProdfin)->mdlEditProdcapacity($data);
    }
	
	static public function ctrShowProdcapacityTransactionList($classcode, $etype){
		$answer = (new ModelProdfin)->mdlShowProdcapacityTransactionList($classcode, $etype);
		return $answer;
	}	

	static public function ctrShowProdcapacity($capacitynumber){
		$answer = (new ModelProdfin)->mdlShowProdcapacity($capacitynumber);
		return $answer;
	}

	static public function ctrShowProdcapacityItems($capacitynumber, $etype){
		$products = (new ModelProdfin)->mdlShowProdcapacityItems($capacitynumber, $etype);
		return $products;
	}

	// Quota Report
	static public function ctrShowQuotaReport($machineid, $start_date, $end_date, $categorycode, $etype, $operatedby, $prodstatus, $reptype, $shift){
		$answer = (new ModelProdfin)->mdlShowQuotaReport($machineid, $start_date, $end_date, $categorycode, $etype, $operatedby, $prodstatus, $reptype, $shift);
		return $answer;
	}
}