<?php
require_once "connection.php";
class ModelEmployees{
	static public function mdlAddEmployee($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $emp_id = $pdo->prepare("SELECT CONCAT('EM', LPAD((count(id)+1),5,'0')) as gen_id  FROM employees FOR UPDATE");

            $emp_id->execute();
		    $empid = $emp_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO employees(empid, isactive, lname, fname, mi, bday, gender, address, mobile, idPos, sssno, phino, pagibig, tin, estatus) VALUES (:empid, :isactive, :lname, :fname, :mi, :bday, :gender, :address, :mobile, :idPos, :sssno, :phino, :pagibig, :tin, :estatus)");

			$stmt->bindParam(":empid", $empid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
			$stmt->bindParam(":lname", $data["lname"], PDO::PARAM_STR);
			$stmt->bindParam(":fname", $data["fname"], PDO::PARAM_STR);
			$stmt->bindParam(":mi", $data["mi"], PDO::PARAM_STR);
			$stmt->bindParam(":bday", $data["bday"], PDO::PARAM_STR);
			$stmt->bindParam(":gender", $data["gender"], PDO::PARAM_STR);
			$stmt->bindParam(":address", $data["address"], PDO::PARAM_STR);
			$stmt->bindParam(":mobile", $data["mobile"], PDO::PARAM_STR);
			$stmt->bindParam(":idPos", $data["idPos"], PDO::PARAM_INT);
			$stmt->bindParam(":sssno", $data["sssno"], PDO::PARAM_STR);
			$stmt->bindParam(":phino", $data["phino"], PDO::PARAM_STR);
			$stmt->bindParam(":pagibig", $data["pagibig"], PDO::PARAM_STR);
			$stmt->bindParam(":tin", $data["tin"], PDO::PARAM_STR);
			$stmt->bindParam(":estatus", $data["estatus"], PDO::PARAM_STR);

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

	static public function mdlEditEmployee($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE employees SET empid = :empid, isactive = :isactive, lname = :lname, fname = :fname, mi = :mi, bday = :bday, gender = :gender, address = :address, mobile = :mobile, idPos = :idPos, sssno = :sssno, phino = :phino, pagibig = :pagibig, tin = :tin, estatus = :estatus WHERE id = :id");

			$stmt->bindParam(":id", $data["id"], PDO::PARAM_INT);
			$stmt->bindParam(":empid", $data["empid"], PDO::PARAM_STR);
			$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
			$stmt->bindParam(":lname", $data["lname"], PDO::PARAM_STR);
			$stmt->bindParam(":fname", $data["fname"], PDO::PARAM_STR);
			$stmt->bindParam(":mi", $data["mi"], PDO::PARAM_STR);
			$stmt->bindParam(":bday", $data["bday"], PDO::PARAM_STR);
			$stmt->bindParam(":gender", $data["gender"], PDO::PARAM_STR);
			$stmt->bindParam(":address", $data["address"], PDO::PARAM_STR);
			$stmt->bindParam(":mobile", $data["mobile"], PDO::PARAM_STR);
			$stmt->bindParam(":idPos", $data["idPos"], PDO::PARAM_INT);
			$stmt->bindParam(":sssno", $data["sssno"], PDO::PARAM_STR);
			$stmt->bindParam(":phino", $data["phino"], PDO::PARAM_STR);
			$stmt->bindParam(":pagibig", $data["pagibig"], PDO::PARAM_STR);
			$stmt->bindParam(":tin", $data["tin"], PDO::PARAM_STR);
			$stmt->bindParam(":estatus", $data["estatus"], PDO::PARAM_STR);

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

	static public function mdlShowEmployees($item, $value){
		if($item != null){
			$stmt = (new Connection)->connect()->prepare("SELECT * FROM employees WHERE $item = :$item");
			$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetch();
		}else{
			$stmt = (new Connection)->connect()->prepare("SELECT * FROM employees ORDER BY lname, fname");
			$stmt -> execute();
			return $stmt -> fetchAll();
		}
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlShowEmployeeName($item, $value){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM employees WHERE $item = :$item");
		$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlShowEmployeesPosition(){
		$stmt = (new Connection)->connect()->prepare("SELECT a.id,a.lname,a.fname,a.mi,b.positiondesc,a.mobile,a.estatus FROM employees AS a INNER JOIN position AS b ON (a.idPos = b.id) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	

    /*=============================================
	SHOW POSITION
	=============================================*/
	static public function mdlShowPosition(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM position ORDER BY positiondesc");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}

	static public function mdlShowStatus(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM estatus ORDER BY id");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}	

	static public function mdlShowGender(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM gender ORDER BY id");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}

	static public function mdlUpdateEmployee($table, $item1, $value1, $value){
		$stmt = (new Connection)->connect()->prepare("UPDATE $table SET $item1 = :$item1 WHERE id = :id");
		$stmt -> bindParam(":".$item1, $value1, PDO::PARAM_STR);
		$stmt -> bindParam(":id", $value, PDO::PARAM_STR);
		if($stmt -> execute()){
			return "ok";
		}else{
			return "error";	
		}
		$stmt -> close();
		$stmt = null;
	}

	// Release Transaction
	static public function mdlOrderedBy(){
		$stmt = (new Connection)->connect()->prepare("SELECT DISTINCT a.lname, a.fname, a.empid FROM employees AS a INNER JOIN purchaseorder AS b ON (a.empid = b.orderedby) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}

	static public function mdlReporter(){
		$stmt = (new Connection)->connect()->prepare("SELECT DISTINCT a.lname, a.fname, a.empid FROM employees AS a INNER JOIN machinetracking AS b ON (a.empid = b.reporter) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}

	static public function mdlPurchaser(){
		$stmt = (new Connection)->connect()->prepare("SELECT DISTINCT a.lname, a.fname, a.empid FROM employees AS a INNER JOIN purchaseorder AS b ON (a.empid = b.preparedby) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}	

	// Release Transaction
	static public function mdlReleaser(){
		$stmt = (new Connection)->connect()->prepare("SELECT DISTINCT a.lname, a.fname, a.empid FROM employees AS a INNER JOIN stockout AS b ON (a.empid = b.postedby) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}

	// Material Request -----------------------------------------------------------------------------
	static public function mdlRequestor(){
		$stmt = (new Connection)->connect()->prepare("SELECT DISTINCT a.lname, a.fname, a.empid FROM employees AS a INNER JOIN rawout AS b ON (a.empid = b.requestby) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}	

	static public function mdlRequestEncoder(){
		$stmt = (new Connection)->connect()->prepare("SELECT DISTINCT a.lname, a.fname, a.empid FROM employees AS a INNER JOIN rawout AS b ON (a.empid = b.postedby) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}

	// Waste/Damages Auditors
	static public function mdlAuditor(){
		$stmt = (new Connection)->connect()->prepare("SELECT DISTINCT a.lname, a.fname, a.empid FROM employees AS a INNER JOIN debris AS b ON (a.empid = b.debrisby) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}	

	static public function mdlDebrisEncoder(){
		$stmt = (new Connection)->connect()->prepare("SELECT DISTINCT a.lname, a.fname, a.empid FROM employees AS a INNER JOIN debris AS b ON (a.empid = b.postedby) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}
	
	// Excess Materials
	static public function mdlExcessOperator(){
		$stmt = (new Connection)->connect()->prepare("SELECT DISTINCT a.lname, a.fname, a.empid FROM employees AS a INNER JOIN excess AS b ON (a.empid = b.operatedby) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}	

	static public function mdlExcessEncoder(){
		$stmt = (new Connection)->connect()->prepare("SELECT DISTINCT a.lname, a.fname, a.empid FROM employees AS a INNER JOIN excess AS b ON (a.empid = b.postedby) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}	

	// Recycle
	static public function mdlRecycler(){
		$stmt = (new Connection)->connect()->prepare("SELECT DISTINCT a.lname, a.fname, a.empid FROM employees AS a INNER JOIN recycle AS b ON (a.empid = b.recycleby) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}			

	// Product Components Operators
	static public function mdlComponentsOperators(){
		$stmt = (new Connection)->connect()->prepare("SELECT DISTINCT a.lname, a.fname, a.empid FROM employees AS a INNER JOIN prodcom AS b ON (a.empid = b.operatedby) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}	

	static public function mdlComponentsEncoder(){
		$stmt = (new Connection)->connect()->prepare("SELECT DISTINCT a.lname, a.fname, a.empid FROM employees AS a INNER JOIN prodcom AS b ON (a.empid = b.postedby) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}	

	// Recycle Operators
	static public function mdlRecycleOperators(){
		$stmt = (new Connection)->connect()->prepare("SELECT DISTINCT a.lname, a.fname, a.empid FROM employees AS a INNER JOIN recycle AS b ON (a.empid = b.recycleby) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}	

	static public function mdlRecycleEncoder(){
		$stmt = (new Connection)->connect()->prepare("SELECT DISTINCT a.lname, a.fname, a.empid FROM employees AS a INNER JOIN recycle AS b ON (a.empid = b.postedby) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}	

	// Final Production Operators
	static public function mdlFinalProductionOperators(){
		$stmt = (new Connection)->connect()->prepare("SELECT DISTINCT a.lname, a.fname, a.empid FROM employees AS a INNER JOIN prodfin AS b ON (a.empid = b.operatedby) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}	

	static public function mdlFinalProductionEncoder(){
		$stmt = (new Connection)->connect()->prepare("SELECT DISTINCT a.lname, a.fname, a.empid FROM employees AS a INNER JOIN prodfin AS b ON (a.empid = b.postedby) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}		

	// Return Transaction
	static public function mdlReceiver(){
		$stmt = (new Connection)->connect()->prepare("SELECT DISTINCT a.lname, a.fname, a.empid FROM employees AS a INNER JOIN returned AS b ON (a.empid = b.postedby) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}

	static public function mdlReturnby(){
		$stmt = (new Connection)->connect()->prepare("SELECT DISTINCT a.lname, a.fname, a.empid FROM employees AS a INNER JOIN returned AS b ON (a.empid = b.returnby) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}	

	// Materials Return -------------------------------------------------------------------------
	static public function mdlReturner(){
		$stmt = (new Connection)->connect()->prepare("SELECT DISTINCT a.lname, a.fname, a.empid FROM employees AS a INNER JOIN matreturn AS b ON (a.empid = b.returnby) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}	

	static public function mdlReturnerEncoder(){
		$stmt = (new Connection)->connect()->prepare("SELECT DISTINCT a.lname, a.fname, a.empid FROM employees AS a INNER JOIN matreturn AS b ON (a.empid = b.postedby) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}
	
	static public function mdlShowDepartmentList(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM department ORDER BY deptname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	
}