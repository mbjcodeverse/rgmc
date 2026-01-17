<?php
require_once "connection.php";
class ModelBuilding{
	static public function mdlAddBuilding($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $building_id = $pdo->prepare("SELECT CONCAT('', LPAD((count(id)+1),4,'0')) as gen_id FROM building FOR UPDATE");

            $building_id->execute();
		    $building_code = $building_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO building(buildingcode, buildingname) VALUES (:buildingcode, :buildingname)");

			$stmt->bindParam(":buildingcode", $building_code[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":buildingname", $data["buildingname"], PDO::PARAM_STR);

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

	static public function mdlEditBuilding($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE building SET buildingcode = :buildingcode, buildingname = :buildingname WHERE id = :id");

			$stmt->bindParam(":id", $data["id"], PDO::PARAM_INT);
			$stmt->bindParam(":buildingcode", $data["buildingcode"], PDO::PARAM_STR);
			$stmt->bindParam(":buildingname", $data["buildingname"], PDO::PARAM_STR);

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

	static public function mdlShowBuilding($item, $value){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM building WHERE $item = :$item");
		$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlShowBuildingList(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM building ORDER BY buildingname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}
}