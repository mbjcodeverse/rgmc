<?php
require_once "connection.php";
class ModelSupplier{
	static public function mdlAddSupplier($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $supp_id = $pdo->prepare("SELECT CONCAT('S', LPAD((count(id)+1),5,'0')) as gen_id  FROM supplier FOR UPDATE");

            $supp_id->execute();
		    $suppid = $supp_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO supplier(suppliercode, name, tin, vatdesc, description, mobile, landline, faxnum, website, contactperson, country, isactive, address) VALUES (:suppliercode, :name, :tin, :vatdesc, :description, :mobile, :landline, :faxnum, :website, :contactperson, :country, :isactive, :address)");

			$stmt->bindParam(":suppliercode", $suppid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":name", $data["name"], PDO::PARAM_STR);
			$stmt->bindParam(":tin", $data["tin"], PDO::PARAM_STR);
			$stmt->bindParam(":vatdesc", $data["vatdesc"], PDO::PARAM_STR);
			$stmt->bindParam(":description", $data["description"], PDO::PARAM_STR);
			$stmt->bindParam(":mobile", $data["mobile"], PDO::PARAM_STR);
			$stmt->bindParam(":landline", $data["landline"], PDO::PARAM_STR);
			$stmt->bindParam(":faxnum", $data["faxnum"], PDO::PARAM_STR);
			$stmt->bindParam(":website", $data["website"], PDO::PARAM_STR);
			$stmt->bindParam(":contactperson", $data["contactperson"], PDO::PARAM_STR);
			$stmt->bindParam(":country", $data["country"], PDO::PARAM_STR);
			$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
			$stmt->bindParam(":address", $data["address"], PDO::PARAM_STR);

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

	static public function mdlEditSupplier($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE supplier SET suppliercode = :suppliercode, name = :name, tin = :tin, vatdesc = :vatdesc, description = :description, mobile = :mobile, landline = :landline, faxnum = :faxnum, website = :website, contactperson = :contactperson, country = :country, isactive = :isactive, address = :address WHERE id = :id");

			$stmt->bindParam(":id", $data["id"], PDO::PARAM_INT);
			$stmt->bindParam(":suppliercode", $data["suppliercode"], PDO::PARAM_STR);
			$stmt->bindParam(":name", $data["name"], PDO::PARAM_STR);
			$stmt->bindParam(":tin", $data["tin"], PDO::PARAM_STR);
			$stmt->bindParam(":vatdesc", $data["vatdesc"], PDO::PARAM_STR);
			$stmt->bindParam(":description", $data["description"], PDO::PARAM_STR);
			$stmt->bindParam(":mobile", $data["mobile"], PDO::PARAM_STR);
			$stmt->bindParam(":landline", $data["landline"], PDO::PARAM_STR);
			$stmt->bindParam(":faxnum", $data["faxnum"], PDO::PARAM_STR);
			$stmt->bindParam(":website", $data["website"], PDO::PARAM_STR);
			$stmt->bindParam(":contactperson", $data["contactperson"], PDO::PARAM_STR);
			$stmt->bindParam(":country", $data["country"], PDO::PARAM_STR);
			$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
			$stmt->bindParam(":address", $data["address"], PDO::PARAM_STR);

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

	static public function mdlShowSupplier($item, $value){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM supplier WHERE $item = :$item");
		$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlShowSupplierList(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM supplier ORDER BY name");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}
}