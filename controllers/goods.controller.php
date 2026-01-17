<?php
class ControllerGoods{
	static public function ctrCreateGoods($data){
	   	$answer = (new ModelGoods)->mdlAddGoods($data);
	}

	static public function ctrEditGoods($data){
	   	$answer = (new ModelGoods)->mdlEditGoods($data);
	}	

	static public function ctrShowAllGoods(){
		$answer = (new ModelGoods)->mdlShowAllGoods();
		return $answer;
	}	

	static public function ctrShowGoodsList($categorycode){
		$answer = (new ModelGoods)->mdlShowGoodsList($categorycode);
		return $answer;
	}

	static public function ctrShowGood($item, $value){
		$answer = (new ModelGoods)->mdlShowGood($item, $value);
		return $answer;
	}	

	static public function ctrShowPurchaseGoodProducts(){
		$answer = (new ModelGoods)->mdlShowPurchaseGoodProducts();
		return $answer;
	}	

	static public function ctrShowTransactionGood($itemid){
		$answer = (new ModelGoods)->mdlShowTransactionGood($itemid);
		return $answer;
	}	

	// static public function ctrShowProdStocks($itemid){
	// 	$answer = (new ModelGoods)->mdlShowProdStocks($itemid);
	// 	return $answer;
	// }	

	// static public function ctrGoodReport($reptype, $filter, $categorycode){
	// 	$answer = (new ModelGoods)->mdlGoodReport($reptype, $filter, $categorycode);
	// 	return $answer;
	// }	
}