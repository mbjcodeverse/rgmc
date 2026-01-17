<?php
require_once "connection.php";

class MeasureModel{
	// static public function mdlShowAllMeasure(){
		// $stmt = Connection::connect()->prepare("SELECT * FROM measure ORDER BY mdesc");
	// 	$stmt = (new Connection)->connect()->prepare("SELECT * FROM measure ORDER BY mdesc")
	// 	$stmt -> execute();
	// 	return $stmt -> fetchAll();
	// 	$stmt -> close();
	// 	$stmt = null;
	// }

	static public function mdlShowAllMeasure(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM measure ORDER BY mdesc");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

	// static public function mdlShowMeasure($item, $value){
	// 	if($item != null){
	// 		$stmt = Connection::connect()->prepare("SELECT * FROM measure WHERE $item = :$item");
	// 		$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
	// 		$stmt -> execute();
	// 		return $stmt -> fetch();
	// 	}
	// 	else{
	// 		$stmt = Connection::connect()->prepare("SELECT * FROM $table");
	// 		$stmt -> execute();
	// 		return $stmt -> fetchAll();
	// 	}
	// 	$stmt -> close();
	// 	$stmt = null;
	// }
}