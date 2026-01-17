<?php
require_once "connection.php";
class ModelOthercost{
	static public function mdlAddOthercost($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $item_id = $pdo->prepare("SELECT CONCAT('OC',LPAD((count(id)+1),5,'0')) as gen_id  FROM othercost FOR UPDATE");

            $item_id->execute();
		    $ocostid = $item_id -> fetchAll(PDO::FETCH_ASSOC);

            $ostatus = 'Posted';
			$stmt = $pdo->prepare("INSERT INTO othercost(ocostid, electricity, manpower, sales, ostatus, odate, postedby)
													  VALUES (:ocostid, :electricity, :manpower, :sales, :ostatus, :odate, :postedby)");

			$stmt->bindParam(":ocostid", $ocostid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":electricity", $data["electricity"], PDO::PARAM_STR);
			$stmt->bindParam(":manpower", $data["manpower"], PDO::PARAM_STR);
			$stmt->bindParam(":sales", $data["sales"], PDO::PARAM_STR);
			$stmt->bindParam(":ostatus", $ostatus, PDO::PARAM_STR);
			$stmt->bindParam(":odate", $data["odate"], PDO::PARAM_STR);
            $stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
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

	static public function mdlEditOthercost($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
            $ostatus = 'Posted';
			$stmt = $pdo->prepare("UPDATE othercost SET ocostid = :ocostid,
                                                               electricity = :electricity,
                                                               manpower = :manpower,
															   sales = :sales,
                                                               ostatus = :ostatus,
                                                               odate = :odate
                                            WHERE ocostid = :ocostid");

			$stmt->bindParam(":ocostid", $data["ocostid"], PDO::PARAM_STR);
			$stmt->bindParam(":electricity", $data["electricity"],PDO::PARAM_STR);
			$stmt->bindParam(":manpower", $data["manpower"], PDO::PARAM_STR);
			$stmt->bindParam(":sales", $data["sales"], PDO::PARAM_STR);
			$stmt->bindParam(":ostatus", $ostatus, PDO::PARAM_STR);
			$stmt->bindParam(":odate", $data["odate"], PDO::PARAM_STR);
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

	static public function mdlShowOthercostTransactionList($start_date, $end_date, $empid, $ostatus){
		if ($empid != ''){
			$posted_by = " AND (a.postedby = '$empid')";
		}else{
			$posted_by = "";
		}	

		if ($ostatus != ''){
			$status = " AND (a.ostatus = '$ostatus')";
		}else{
			$status = "";
		}

		if(!empty($end_date)){
			$dates = " AND (a.odate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (a.ocostid != '')" . $posted_by . $status . $dates;

		$stmt = (new Connection)->connect()->prepare("SELECT CONCAT(b.lname,', ',b.fname) AS postedby,a.ocostid,a.odate,a.electricity,a.manpower,a.sales FROM othercost AS a INNER JOIN employees AS b ON (a.postedby = b.empid) $whereClause ORDER BY a.odate");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

    static public function mdlShowOthercost($ocostid){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM othercost WHERE (ocostid = '$ocostid')");

		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}    
}