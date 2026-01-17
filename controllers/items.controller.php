<?php
class ControllerItems{
	static public function ctrCreateItems($data){
	   	$answer = (new ModelItems)->mdlAddItems($data);
	}

	static public function ctrEditItems($data){
	   	$answer = (new ModelItems)->mdlEditItems($data);
	}	

	static public function ctrShowAllItems(){
		$answer = (new ModelItems)->mdlShowAllItems();
		return $answer;
	}	

	static public function ctrShowItemsList($categorycode){
		$answer = (new ModelItems)->mdlShowItemsList($categorycode);
		return $answer;
	}

	static public function ctrShowItem($item, $value){
		$answer = (new ModelItems)->mdlShowItem($item, $value);
		return $answer;
	}	

	static public function ctrShowPurchaseItemProducts(){
		$answer = (new ModelItems)->mdlShowPurchaseItemProducts();
		return $answer;
	}	

	static public function ctrShowTransactionItem($itemid){
		$answer = (new ModelItems)->mdlShowTransactionItem($itemid);
		return $answer;
	}	

	// static public function ctrShowProdStocks($itemid){
	// 	$answer = (new ModelItems)->mdlShowProdStocks($itemid);
	// 	return $answer;
	// }
	
	static public function ctrShowProdStocks($itemid, $asofdate){
		$answer = (new ModelItems)->mdlShowProdStocks($itemid, $asofdate);
		return $answer;
	}	

	static public function ctrItemReport($reptype, $filter, $categorycode){
		$answer = (new ModelItems)->mdlItemReport($reptype, $filter, $categorycode);
		return $answer;
	}	
}