<?php
class ControllerOthercost{
	static public function ctrCreateOthercost($data){
	   	$answer = (new ModelOthercost)->mdlAddOthercost($data);
	}

	static public function ctrEditOthercost($data){
	   	$answer = (new ModelOthercost)->mdlEditOthercost($data);
	}

	static public function ctrShowOthercostTransactionList($start_date, $end_date, $empid, $ostatus){
		$answer = (new ModelOthercost)->mdlShowOthercostTransactionList($start_date, $end_date, $empid, $ostatus);
		return $answer;
	}
    
	static public function ctrShowOthercost($ocostid){
		$answer = (new ModelOthercost)->mdlShowOthercost($ocostid);
		return $answer;
	}    
}