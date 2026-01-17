<?php
class ControllerReport{
	static public function ctrShowCatdescReport($start_date,$end_date,$category,$trans_type){
		$answer = (new ModelReport)->mdlShowCatdescReport($start_date,$end_date,$category,$trans_type);
		return $answer;
	}

	static public function ctrShowSequenceReport($start_date,$end_date,$category,$trans_type){
		$answer = (new ModelReport)->mdlShowSequenceReport($start_date,$end_date,$category,$trans_type);
		return $answer;
	}	
}
