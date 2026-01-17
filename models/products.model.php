<?php
require_once 'connection.php';
class ModelProducts{
	static public function mdlShowProdStocks($idProduct){
		// $stmt = (new Connection)->connect()->prepare("SELECT a.id,b.tdate,b.refcode AS tcode,'Inventory' AS details,a.pdesc,c.qty,1 AS priority,CONCAT(d.fname, ' ', d.lname) AS transinfo,'' FROM products AS a, inventory AS b, inventoryitems AS c, employees AS d WHERE (a.id = c.idProduct) AND (c.refcode = b.refcode) AND (b.idEmployee = d.id) AND (b.tstatus = 'Committed') AND (b.tdate >= '2021-11-01') AND (a.id = $idProduct) UNION ALL SELECT a.id,b.tdate,b.refcode AS tcode,'Stock-in' AS details,a.pdesc,c.qty,2 AS priority,d.stkname AS transinfo,e.rescat FROM products AS a, stockin AS b, stockinitems AS c, stakeholder AS d, reason AS e WHERE (a.id = c.idProduct) AND (c.refcode = b.refcode) AND (b.idStakeholder = d.id) AND (b.idReason = e.id) AND (b.tstatus = 'Committed') AND (b.tdate >= '2021-11-01') AND (a.id = $idProduct) UNION ALL SELECT a.id,b.tdate,b.refcode AS tcode,'Stock-out' AS details,a.pdesc,c.qty,3 AS priority,d.stkname AS transinfo,e.rescat FROM products AS a, stockout AS b, stockoutitems AS c, stakeholder AS d, reason AS e WHERE (a.id = c.idProduct) AND (c.refcode = b.refcode) AND (b.idStakeholder = d.id) AND (b.idReason = e.id) AND (b.tstatus = 'Committed') AND (b.tdate >= '2021-11-01') AND (a.id = $idProduct) ORDER BY id,tdate,priority");

		$stmt = (new Connection)->connect()->prepare("SELECT a.id,b.tdate,b.refcode AS tcode,'Inventory' AS details,a.pdesc,c.qty,1 AS priority,CONCAT(d.fname, ' ', d.lname) AS transinfo,'' FROM products AS a, inventory AS b, inventoryitems AS c, employees AS d WHERE (a.id = c.idProduct) AND (c.refcode = b.refcode) AND (b.idEmployee = d.id) AND (b.tstatus = 'Committed') AND (b.tdate >= '2021-11-01') AND (a.id = $idProduct) UNION ALL SELECT a.id,b.tdate,b.refcode AS tcode,'Stock-in' AS details,a.pdesc,c.qty,2 AS priority,'' AS transinfo,e.rescat FROM products AS a, stockin AS b, stockinitems AS c, reason AS e WHERE (a.id = c.idProduct) AND (c.refcode = b.refcode) AND (b.idReason = e.id) AND (b.tstatus = 'Committed') AND (b.tdate >= '2021-11-01') AND (a.id = $idProduct) UNION ALL SELECT a.id,b.tdate,b.refcode AS tcode,'Stock-out' AS details,a.pdesc,c.qty,3 AS priority,d.stkname AS transinfo,e.rescat FROM products AS a, stockout AS b, stockoutitems AS c, stakeholder AS d, reason AS e WHERE (a.id = c.idProduct) AND (c.refcode = b.refcode) AND (b.idStakeholder = d.id) AND (b.idReason = e.id) AND (b.tstatus = 'Committed') AND (b.tdate >= '2021-11-01') AND (a.id = $idProduct) ORDER BY id,tdate,priority");		

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlShowCategory(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM category ORDER BY catdesc");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}

	static public function mdlShowProducts(){
		$stmt = (new Connection)->connect()->prepare("SELECT a.id,a.pdesc,b.mdesc as mdesc1,a.eqnum,a.idMeas2 FROM products as a,measure as b WHERE (a.idMeas1 = b.id) AND (a.isactive = 1) ORDER BY pdesc");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}	

	static public function mdlGetProduct($item, $value){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM products WHERE $item = :$item ORDER BY id ASC");
		$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlGetProductCategory($item, $value){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM category WHERE $item = :$item ORDER BY id ASC");
		$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlAddProduct($table, $data){
		$stmt = (new Connection)->connect()->prepare("INSERT INTO $table(idCategory, pdesc, ucost, isactive, prodid, idMeas1, eqnum, idMeas2, remarks) VALUES (:idCategory, :pdesc, :ucost, :isactive, :prodid, :idMeas1, :eqnum, :idMeas2, :remarks)");

		$stmt->bindParam(":idCategory", $data["idCategory"], PDO::PARAM_INT);
		$stmt->bindParam(":pdesc", $data["pdesc"], PDO::PARAM_STR);
		$stmt->bindParam(":ucost", $data["ucost"], PDO::PARAM_STR);
		$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
		$stmt->bindParam(":prodid", $data["prodid"], PDO::PARAM_STR);
		$stmt->bindParam(":idMeas1", $data["idMeas1"], PDO::PARAM_INT);
		$stmt->bindParam(":eqnum", $data["eqnum"], PDO::PARAM_STR);
		$stmt->bindParam(":idMeas2", $data["idMeas2"], PDO::PARAM_INT);
		$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);

		if($stmt->execute()){
			return "ok";
		}else{
			return "error";
		}
		$stmt->close();
		$stmt = null;
	}	

	static public function mdlEditProduct($table, $data){
		$stmt = (new Connection)->connect()->prepare("UPDATE $table SET idCategory = :idCategory, pdesc = :pdesc, ucost = :ucost, isactive = :isactive, prodid = :prodid, idMeas1 = :idMeas1, eqnum = :eqnum, idMeas2 = :idMeas2, remarks = :remarks WHERE id = :id");

		$stmt->bindParam(":id", $data["id"], PDO::PARAM_INT);
		$stmt->bindParam(":idCategory", $data["idCategory"], PDO::PARAM_INT);
		$stmt->bindParam(":pdesc", $data["pdesc"], PDO::PARAM_STR);
		$stmt->bindParam(":ucost", $data["ucost"], PDO::PARAM_STR);
		$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
		$stmt->bindParam(":prodid", $data["prodid"], PDO::PARAM_STR);
		$stmt->bindParam(":idMeas1", $data["idMeas1"], PDO::PARAM_INT);
		$stmt->bindParam(":eqnum", $data["eqnum"], PDO::PARAM_STR);
		$stmt->bindParam(":idMeas2", $data["idMeas2"], PDO::PARAM_INT);
		$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);

		if($stmt->execute()){
			return "ok";
		}else{
			return "error";
		}
		$stmt->close();
		$stmt = null;
	}	
}