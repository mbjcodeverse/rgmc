<?php
class ControllerStockout{
	static public function ctrCreateStockout($data){
	   	$answer = (new ModelStockout)->mdlAddStockout($data);
	}

	static public function ctrEditStockout($data){
		$answer = (new ModelStockout)->mdlEditStockout($data);
    }	

    // List TRANSACTIONS
	static public function ctrShowReleasingTransactionList($machineid, $start_date, $end_date, $empid, $reqstatus, $reltype){
		$answer = (new ModelStockout)->mdlShowReleasingTransactionList($machineid, $start_date, $end_date, $empid, $reqstatus, $reltype);
		return $answer;
	}	

	static public function ctrShowStockoutReport($machineid, $start_date, $end_date, $deptcode, $categorycode, $postedby, $requestby, $reqstatus, $reptype){
		$answer = (new ModelStockout)->mdlShowStockoutReport($machineid, $start_date, $end_date, $deptcode, $categorycode, $postedby, $requestby, $reqstatus, $reptype);
		return $answer;
	}

	// Get Releasing Details
	static public function ctrShowReleasing($reqnumber){
		$answer = (new ModelStockout)->mdlShowReleasing($reqnumber);
		return $answer;
	}

    // Get ITEMS
	static public function ctrShowReleasingItems($reqnumber){
		$products = (new ModelStockout)->mdlShowReleasingItems($reqnumber);
		return $products;
	}
}