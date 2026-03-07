<?php
class ControllerSubmetrics{
	static public function ctrCreateSubmetrics($data){
	   	$answer = (new ModelSubmetrics)->mdlAddSubmetrics($data);
	}

	static public function ctrEditSubmetrics($data){
	   	$answer = (new ModelSubmetrics)->mdlEditSubmetrics($data);
	}

	static public function ctrShowSubmetricsTransactionList($start_date, $end_date, $empid, $mstatus){
		$answer = (new ModelSubmetrics)->mdlShowSubmetricsTransactionList($start_date, $end_date, $empid, $mstatus);
		return $answer;
	}
    
	static public function ctrShowSubmetrics($submetid){
		$answer = (new ModelSubmetrics)->mdlShowSubmetrics($submetid);
		return $answer;
	}    
}