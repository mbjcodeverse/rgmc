<?php
require_once "connection.php";
class UsersModel{
	static public function mdlShowUserType(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM utype ORDER BY id");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}

	static public function MdlShowUsers($tableUsers, $item, $value){
		if($item != null){
			$stmt = (new Connection)->connect()->prepare("SELECT * FROM $tableUsers WHERE $item = :$item");
			$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetch();
		}
		else{
			$stmt = (new Connection)->connect()->prepare("SELECT * FROM $tableUsers");
			$stmt -> execute();
			return $stmt -> fetchAll();
		}
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlAddUser($table, $data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $user_id = $pdo->prepare("SELECT CONCAT('U', LPAD((count(id)+1),3,'0')) as gen_id FROM users");

            $user_id->execute();
		    $user_code = $user_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = (new Connection)->connect()->prepare("INSERT INTO $table(empid, user, password, override, utype, photo, isactive, userid, mt, ins, po, inc, rel, ret, adj, inv, rep, su, em, bd, prt, cat, bnd, mac, cls, ac) VALUES (:empid, :user, :password, :override, :utype, :photo, :isactive, :userid, :mt, :ins, :po, :inc, :rel, :ret, :adj, :inv, :rep, :su, :em, :bd, :prt, :cat, :bnd, :mac, :cls, :ac)");

			$stmt -> bindParam(":empid", $data["empid"], PDO::PARAM_STR);
			$stmt -> bindParam(":user", $data["user"], PDO::PARAM_STR);
			$stmt -> bindParam(":password", $data["password"], PDO::PARAM_STR);
			$stmt -> bindParam(":override", $data["override"], PDO::PARAM_STR);
			$stmt -> bindParam(":utype", $data["utype"], PDO::PARAM_STR);
			$stmt -> bindParam(":photo", $data["photo"], PDO::PARAM_STR);
			$stmt -> bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
			$stmt -> bindParam(":userid", $user_code[0]['gen_id'], PDO::PARAM_STR);
			$stmt -> bindParam(":mt", $data["mt"], PDO::PARAM_INT);
			$stmt -> bindParam(":ins", $data["ins"], PDO::PARAM_INT);
			$stmt -> bindParam(":po", $data["po"], PDO::PARAM_INT);
			$stmt -> bindParam(":inc", $data["inc"], PDO::PARAM_INT);
			$stmt -> bindParam(":rel", $data["rel"], PDO::PARAM_INT);
			$stmt -> bindParam(":ret", $data["ret"], PDO::PARAM_INT);
			$stmt -> bindParam(":adj", $data["adj"], PDO::PARAM_INT);
			$stmt -> bindParam(":inv", $data["inv"], PDO::PARAM_INT);
			$stmt -> bindParam(":rep", $data["rep"], PDO::PARAM_INT);
			$stmt -> bindParam(":su", $data["su"], PDO::PARAM_INT);
			$stmt -> bindParam(":em", $data["em"], PDO::PARAM_INT);
			$stmt -> bindParam(":bd", $data["bd"], PDO::PARAM_INT);
			$stmt -> bindParam(":prt", $data["prt"], PDO::PARAM_INT);
			$stmt -> bindParam(":cat", $data["cat"], PDO::PARAM_INT);
			$stmt -> bindParam(":bnd", $data["bnd"], PDO::PARAM_INT);
			$stmt -> bindParam(":mac", $data["mac"], PDO::PARAM_INT);
			$stmt -> bindParam(":cls", $data["cls"], PDO::PARAM_INT);
			$stmt -> bindParam(":ac", $data["ac"], PDO::PARAM_INT);

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

	static public function mdlEditUser($table, $data){
		$stmt = (new Connection)->connect()->prepare("UPDATE $table set idEmployee = :idEmployee, user = :user, password = :password, override = :override, utype = :utype, photo = :photo, isactive = :isactive, mt = :mt, ins = :ins, po = :po, inc = :inc, rel = :rel, ret = :ret, adj = :adj, inv = :inv, rep = :rep, su = :su, em = :em, bd = :bd, prt = :prt, cat = :cat, bnd = :bnd, mac = :mac, cls = :cls, ac = :ac WHERE id = :id");

		$stmt -> bindParam(":id", $data["id"], PDO::PARAM_INT);
		$stmt -> bindParam(":idEmployee", $data["idEmployee"], PDO::PARAM_INT);
		$stmt -> bindParam(":user", $data["user"], PDO::PARAM_STR);
		$stmt -> bindParam(":password", $data["password"], PDO::PARAM_STR);
		$stmt -> bindParam(":override", $data["override"], PDO::PARAM_STR);
		$stmt -> bindParam(":utype", $data["utype"], PDO::PARAM_STR);
		$stmt -> bindParam(":photo", $data["photo"], PDO::PARAM_STR);
		$stmt -> bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
		$stmt -> bindParam(":mt", $data["mt"], PDO::PARAM_INT);
		$stmt -> bindParam(":ins", $data["ins"], PDO::PARAM_INT);
		$stmt -> bindParam(":po", $data["po"], PDO::PARAM_INT);
		$stmt -> bindParam(":inc", $data["inc"], PDO::PARAM_INT);
		$stmt -> bindParam(":rel", $data["rel"], PDO::PARAM_INT);
		$stmt -> bindParam(":ret", $data["ret"], PDO::PARAM_INT);
		$stmt -> bindParam(":adj", $data["adj"], PDO::PARAM_INT);
		$stmt -> bindParam(":inv", $data["inv"], PDO::PARAM_INT);
		$stmt -> bindParam(":rep", $data["rep"], PDO::PARAM_INT);
		$stmt -> bindParam(":su", $data["su"], PDO::PARAM_INT);
		$stmt -> bindParam(":em", $data["em"], PDO::PARAM_INT);
		$stmt -> bindParam(":bd", $data["bd"], PDO::PARAM_INT);
		$stmt -> bindParam(":prt", $data["prt"], PDO::PARAM_INT);
		$stmt -> bindParam(":cat", $data["cat"], PDO::PARAM_INT);
		$stmt -> bindParam(":bnd", $data["bnd"], PDO::PARAM_INT);
		$stmt -> bindParam(":mac", $data["mac"], PDO::PARAM_INT);
		$stmt -> bindParam(":cls", $data["cls"], PDO::PARAM_INT);
		$stmt -> bindParam(":ac", $data["ac"], PDO::PARAM_INT);

		if ($stmt->execute()) {
			return 'ok';
		} else {
			return 'error';
		}
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlUpdateUser($table, $item1, $value1, $item2, $value2){
		$stmt = (new Connection)->connect()->prepare("UPDATE $table set $item1 = :$item1 WHERE $item2 = :$item2");
		$stmt -> bindParam(":".$item1, $value1, PDO::PARAM_INT);
		$stmt -> bindParam(":".$item2, $value2, PDO::PARAM_INT);
		if ($stmt->execute()) {
			return 'ok';
		} else {
			return 'error';
		}
		$stmt -> close();
		$stmt = null;
	}
	/*=============================================
	DELETE USER 
	=============================================*/	
	static public function mdlDeleteUser($table, $data){
		$stmt = (new Connection)->connect()->prepare("DELETE FROM users WHERE id = :id");
		$stmt -> bindParam(":id", $data, PDO::PARAM_STR);
		if ($stmt->execute()) {
			return 'ok';
		} else {
			return 'error';
		}
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlGetOverrideKey($override_key){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM users WHERE override = '$override_key'");
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}
}