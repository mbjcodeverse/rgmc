<?php
class ControllerRawout{
	static public function ctrCreateRawout($data){
	   	$answer = (new ModelRawout)->mdlAddRawout($data);
	}

	static public function ctrEditRawout($data){
		$answer = (new ModelRawout)->mdlEditRawout($data);
    }	

    // List TRANSACTIONS
	static public function ctrShowRawoutTransactionList($machineid, $start_date, $end_date, $empid, $reqstatus){
		$answer = (new ModelRawout)->mdlShowRawoutTransactionList($machineid, $start_date, $end_date, $empid, $reqstatus);
		return $answer;
	}	

	static public function ctrShowRawoutReport($machineid, $start_date, $end_date, $categorycode, $postedby, $requestby, $reqstatus, $reptype, $costype){
		$answer = (new ModelRawout)->mdlShowRawoutReport($machineid, $start_date, $end_date, $categorycode, $postedby, $requestby, $reqstatus, $reptype, $costype);
		return $answer;
	}

	// Get Rawout Details
	static public function ctrShowRawout($reqnumber){
		$answer = (new ModelRawout)->mdlShowRawout($reqnumber);
		return $answer;
	}

    // Get ITEMS
	static public function ctrShowRawoutItems($reqnumber){
		$products = (new ModelRawout)->mdlShowRawoutItems($reqnumber);
		return $products;
	}

	static public function ctrCancelRawout($reqnumber){
		$answer = (new ModelRawout)->mdlCancelRawout($reqnumber);
		return $answer;
	}	
}