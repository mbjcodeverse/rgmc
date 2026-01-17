<?php
class ControllerRecycle{
	static public function ctrCreateRecycle($data){
	   	$answer = (new ModelRecycle)->mdlAddRecycle($data);
	}

	static public function ctrEditRecycle($data){
		$answer = (new ModelRecycle)->mdlEditRecycle($data);
    }	

    // List TRANSACTIONS
	static public function ctrShowRecycleTransactionList($machineid, $start_date, $end_date, $empid, $recstatus){
		$answer = (new ModelRecycle)->mdlShowRecycleTransactionList($machineid, $start_date, $end_date, $empid, $recstatus);
		return $answer;
	}	

	static public function ctrShowRecycleReport($machineid, $start_date, $end_date, $categorycode, $postedby, $recycleby, $recstatus, $reptype){
		$answer = (new ModelRecycle)->mdlShowRecycleReport($machineid, $start_date, $end_date, $categorycode, $postedby, $recycleby, $recstatus, $reptype);
		return $answer;
	}

	// Get Recycle Details
	static public function ctrShowRecycle($recnumber){
		$answer = (new ModelRecycle)->mdlShowRecycle($recnumber);
		return $answer;
	}

    // Get ITEMS
	static public function ctrShowRecycleItems($recnumber){
		$products = (new ModelRecycle)->mdlShowRecycleItems($recnumber);
		return $products;
	}	

	static public function ctrCancelRecycle($recnumber){
		$answer = (new ModelRecycle)->mdlCancelRecycle($recnumber);
		return $answer;
	}	
}