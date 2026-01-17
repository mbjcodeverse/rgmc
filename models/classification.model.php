<?php
require_once "connection.php";
class ModelClassification{
	static public function mdlAddClassification($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $class_id = $pdo->prepare("SELECT CONCAT('', LPAD((count(id)+1),4,'0')) as gen_id FROM classification FOR UPDATE");

            $class_id->execute();
		    $class_code = $class_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO classification(classcode, classname) VALUES (:classcode, :classname)");

			$stmt->bindParam(":classcode", $class_code[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":classname", $data["classname"], PDO::PARAM_STR);

			$stmt->execute();
		    $pdo->commit();
		    return "ok";
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}	
		$pdo = null;	
		$stmt = null;
	}

	static public function mdlEditClassification($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE classification SET classcode = :classcode, classname = :classname WHERE id = :id");

			$stmt->bindParam(":id", $data["id"], PDO::PARAM_INT);
			$stmt->bindParam(":classcode", $data["classcode"], PDO::PARAM_STR);
			$stmt->bindParam(":classname", $data["classname"], PDO::PARAM_STR);

			$stmt->execute();  

		    $pdo->commit();
		    return "ok";
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}	
		$pdo = null;	
		$stmt = null;
	}	

	static public function mdlShowClassification($item, $value){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM classification WHERE $item = :$item");
		$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlShowClassificationList(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM classification ORDER BY classname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}
}