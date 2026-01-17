<?php
class ControllerProductmetrics{
	static public function ctrCreateProductmetrics($data){
	   	$answer = (new ModelProductmetrics)->mdlAddProductmetrics($data);
	}

	static public function ctrEditProductmetrics($data){
	   	$answer = (new ModelProductmetrics)->mdlEditProductmetrics($data);
	}

	static public function ctrShowProductmetricsTransactionList($start_date, $end_date, $empid, $mstatus){
		$answer = (new ModelProductmetrics)->mdlShowProductmetricsTransactionList($start_date, $end_date, $empid, $mstatus);
		return $answer;
	}
    
	static public function ctrShowProductmetrics($prodmetid){
		$answer = (new ModelProductmetrics)->mdlShowProductmetrics($prodmetid);
		return $answer;
	}    
}