<?php
 class ControllerMeasure{
	// static public function ctrShowAllMeasure(){
	// 	$answer = MeasureModel::mdlShowAllMeasure();
	// 	return $answer;
	// }

	static public function ctrShowAllMeasure(){
		$answer = (new MeasureModel)->mdlShowAllMeasure();
		return $answer;
	}
}