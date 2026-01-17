<?php
class ControllerExcess{
	static public function ctrCreateExcess($data){
	   	$answer = (new ModelExcess)->mdlAddExcess($data);
	}

	static public function ctrEditExcess($data){
		$answer = (new ModelExcess)->mdlEditExcess($data);
    }	

    // List TRANSACTIONS
	static public function ctrShowExcessTransactionList($machineid, $start_date, $end_date, $empid, $excstatus){
		$answer = (new ModelExcess)->mdlShowExcessTransactionList($machineid, $start_date, $end_date, $empid, $excstatus);
		return $answer;
	}	

	static public function ctrShowExcessReport($machineid, $start_date, $end_date, $categorycode, $postedby, $operatedby, $excstatus, $reptype){
		$answer = (new ModelExcess)->mdlShowExcessReport($machineid, $start_date, $end_date, $categorycode, $postedby, $operatedby, $excstatus, $reptype);
		return $answer;
	}

	// Get Excess Details
	static public function ctrShowExcess($excnumber){
		$answer = (new ModelExcess)->mdlShowExcess($excnumber);
		return $answer;
	}

    // Get ITEMS
	static public function ctrShowExcessItems($excnumber){
		$products = (new ModelExcess)->mdlShowExcessItems($excnumber);
		return $products;
	}

	static public function ctrCancelExcess($excnumber){
		$answer = (new ModelExcess)->mdlCancelExcess($excnumber);
		return $answer;
	}	
}