<?php
require_once "connection.php";
class ModelMachine{
	static public function mdlAddMachine($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $machine_id = $pdo->prepare("SELECT CONCAT('', LPAD((count(id)+1),4,'0')) as gen_id FROM machine FOR UPDATE");

            $machine_id->execute();
		    $machine_code = $machine_id -> fetchAll(PDO::FETCH_ASSOC);
			$machine_identifier = $machine_code[0]['gen_id'];

		    //--------------------------------------------------------
		 //    $path = "views/img/machine/default/machine.jpg";
		 //    $next_id = (new Connection)->connect()->query("SHOW TABLE STATUS LIKE 'machine'")->fetch(PDO::FETCH_ASSOC)['Auto_increment'];

			// $folder = "views/img/machine/M".$next_id;
			// mkdir($folder, 0755);

			$tmp_name = $data["image"];

			$random = mt_rand(100,999);
			// $image = "views/img/machine/M".$next_id."/".$random.".jpg";
			$image = "views/img/machine/".$random.".jpg";
			move_uploaded_file($tmp_name, $image);

			//--------------------------------------------------------

			$stmt = $pdo->prepare("INSERT INTO machine(classcode, machtype, machabbr, machinedesc, categorycode, buildingcode, isactive, machstatus, machineid, image, attributelist) VALUES (:classcode, :machtype, :machabbr, :machinedesc, :categorycode, :buildingcode, :isactive, :machstatus, :machineid, :image, :attributelist)");

			$stmt->bindParam(":machineid", $machine_code[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":classcode", $data["classcode"], PDO::PARAM_STR);
			$stmt->bindParam(":machtype", $data["machtype"], PDO::PARAM_STR);
			$stmt->bindParam(":machabbr", $data["machabbr"], PDO::PARAM_STR);
			$stmt->bindParam(":categorycode", $data["categorycode"], PDO::PARAM_STR);
			$stmt->bindParam(":machinedesc", $data["machinedesc"], PDO::PARAM_STR);
			$stmt->bindParam(":buildingcode", $data["buildingcode"], PDO::PARAM_STR);
			$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
			$stmt->bindParam(":machstatus", $data["machstatus"], PDO::PARAM_STR);
			$stmt->bindParam(":image", $image, PDO::PARAM_STR);
			$stmt->bindParam(":attributelist", $data["attributelist"], PDO::PARAM_STR);
			$stmt->execute();

			$attributeList = json_decode($data["attributelist"]);
			foreach($attributeList as $attribute){
				$attributes = $pdo->prepare("INSERT INTO machineattributes(machineid, attribute, detail) VALUES (:machineid, :attribute, :detail)");

				$attributes->bindParam(":machineid", $machine_identifier, PDO::PARAM_STR);
				$attributes->bindParam(":attribute", $attribute->attribute, PDO::PARAM_STR);
				$attributes->bindParam(":detail", $attribute->detail, PDO::PARAM_STR);
				$attributes->execute();
			}

		    $pdo->commit();
		    return "ok";
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}	
		$pdo = null;	
		$stmt = null;
	}

	static public function mdlEditMachine($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE machine SET classcode = :classcode, machtype = :machtype, machabbr = :machabbr, machinedesc = :machinedesc, categorycode = :categorycode, buildingcode = :buildingcode, isactive = :isactive, machstatus = :machstatus, machineid = :machineid, attributelist = :attributelist WHERE machineid = :machineid");

			$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$stmt->bindParam(":classcode", $data["classcode"], PDO::PARAM_STR);
			$stmt->bindParam(":machtype", $data["machtype"], PDO::PARAM_STR);
			$stmt->bindParam(":machabbr", $data["machabbr"], PDO::PARAM_STR);
			$stmt->bindParam(":categorycode", $data["categorycode"], PDO::PARAM_STR);
			$stmt->bindParam(":machinedesc", $data["machinedesc"], PDO::PARAM_STR);
			$stmt->bindParam(":buildingcode", $data["buildingcode"], PDO::PARAM_STR);
			$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
			$stmt->bindParam(":machstatus", $data["machstatus"], PDO::PARAM_STR);
			$stmt->bindParam(":attributelist", $data["attributelist"], PDO::PARAM_STR);
			$stmt->execute();

			// Delete existing Attributes
		    $delete_items = (new Connection)->connect()->prepare("DELETE FROM machineattributes WHERE machineid = :machineid");
		    $delete_items -> bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
		    $delete_items->execute();

		    // Insert updated/new Attributes
			$attributeList = json_decode($data["attributelist"]);
			foreach($attributeList as $attribute){
				$attributes = $pdo->prepare("INSERT INTO machineattributes(machineid, attribute, detail) VALUES (:machineid, :attribute, :detail)");

				$attributes->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
				$attributes->bindParam(":attribute", $attribute->attribute, PDO::PARAM_STR);
				$attributes->bindParam(":detail", $attribute->detail, PDO::PARAM_STR);
				$attributes->execute();
			}

		    $pdo->commit();
		    return "ok";
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}	
		$pdo = null;	
		$stmt = null;
	}

	static public function mdlShowMachine($item, $value){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM machine WHERE $item = :$item");
		$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlShowMachineList(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM machine ORDER BY machinedesc");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlShowMachineListLocation(){
		$stmt = (new Connection)->connect()->prepare("SELECT a.machineid, a.machinedesc, a.machabbr,b.buildingname
															 FROM machine AS a INNER JOIN building AS b
															 ON (a.buildingcode = b.buildingcode)
															 ORDER BY a.machinedesc");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlShowMachineSearchList($classcode, $buildingcode, $isactive, $machstatus){
		if ($classcode != ''){
			$class_code = " AND (a.classcode = '$classcode')";
		}else{
			$class_code = "";
		}

		if ($buildingcode != ''){
			$building_code = " AND (a.buildingcode = '$buildingcode')";
		}else{
			$building_code = "";
		}	
		
		if ($machstatus != ''){
			$mach_status = " AND (a.machstatus = '$machstatus')";
		}else{
			$mach_status = "";
		}		
		
		if ($isactive == '0'){
			$is_active = " AND (a.isactive = 0)";
		}elseif ($isactive == '1'){
			$is_active = " AND (a.isactive = 1)";
		}else{
			$is_active = "";
		}	

		$whereClause = "WHERE (a.machineid != '')" . $class_code . $building_code . $mach_status . $is_active;

		$stmt = (new Connection)->connect()->prepare("SELECT a.machineid, a.machinedesc, b.classname, c.buildingname, a.isactive, a.machstatus FROM machine AS a INNER JOIN classification AS b ON (a.classcode = b.classcode) INNER JOIN building AS c ON (a.buildingcode = c.buildingcode) $whereClause ORDER BY a.machinedesc, c.buildingname");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	
	
	// GET Machine Attributes
	static public function mdlShowAttributes($machineid){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM machineattributes WHERE (machineid = '$machineid')");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}
}