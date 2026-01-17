<?php
class ControllerMatreturn{
	static public function ctrCreateMatreturn($data){
	   	$answer = (new ModelMatreturn)->mdlAddMatreturn($data);
	}

	static public function ctrEditMatreturn($data){
		$answer = (new ModelMatreturn)->mdlEditMatreturn($data);
    }	

    // List TRANSACTIONS
	static public function ctrShowMatreturnTransactionList($machineid, $start_date, $end_date, $empid, $retstatus, $returntype){
		$answer = (new ModelMatreturn)->mdlShowMatreturnTransactionList($machineid, $start_date, $end_date, $empid, $retstatus, $returntype);
		return $answer;
	}	

	static public function ctrShowMatreturnReport($machineid, $start_date, $end_date, $categorycode, $postedby, $returnby, $retstatus, $reptype, $returntype){
		$answer = (new ModelMatreturn)->mdlShowMatreturnReport($machineid, $start_date, $end_date, $categorycode, $postedby, $returnby, $retstatus, $reptype, $returntype);
		return $answer;
	}

	// Get Matreturn Details
	static public function ctrShowMatreturn($retnumber){
		$answer = (new ModelMatreturn)->mdlShowMatreturn($retnumber);
		return $answer;
	}

    // Get ITEMS
	static public function ctrShowMatreturnItems($retnumber){
		$products = (new ModelMatreturn)->mdlShowMatreturnItems($retnumber);
		return $products;
	}

	static public function ctrCancelMatreturn($retnumber){
		$answer = (new ModelMatreturn)->mdlCancelMatreturn($retnumber);
		return $answer;
	}	
}