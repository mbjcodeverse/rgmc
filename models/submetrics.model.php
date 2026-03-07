<?php
require_once "connection.php";
class ModelSubmetrics{
	static public function mdlAddSubmetrics($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $item_id = $pdo->prepare("SELECT CONCAT('SC',LPAD((count(id)+1),5,'0')) as gen_id  FROM submetrics FOR UPDATE");

            $item_id->execute();
		    $submetid = $item_id -> fetchAll(PDO::FETCH_ASSOC);

            $mstatus = 'Posted';
			$stmt = $pdo->prepare("INSERT INTO submetrics(submetid, categorycode, headcount, dailyrate, amount, mstatus, mdate, postedby)
													         VALUES (:submetid, :categorycode, :headcount, :dailyrate, :amount, :mstatus, :mdate, :postedby)");

			$stmt->bindParam(":submetid", $submetid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":categorycode", $data["categorycode"], PDO::PARAM_STR);
			$stmt->bindParam(":headcount", $data["headcount"], PDO::PARAM_STR);
			$stmt->bindParam(":dailyrate", $data["dailyrate"], PDO::PARAM_STR);
            $stmt->bindParam(":amount", $data["amount"], PDO::PARAM_STR);
			$stmt->bindParam(":mstatus", $mstatus, PDO::PARAM_STR);
			$stmt->bindParam(":mdate", $data["mdate"], PDO::PARAM_STR);
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

	static public function mdlEditSubmetrics($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
            $mstatus = 'Posted';
			$stmt = $pdo->prepare("UPDATE submetrics SET submetid = :submetid,
																	categorycode = :categorycode,
																	headcount = :headcount,
																	dailyrate = :dailyrate,
																	amount = :amount,
																	mstatus = :mstatus,
																	mdate = :mdate
													WHERE submetid = :submetid");

			$stmt->bindParam(":submetid", $data["submetid"], PDO::PARAM_STR);
			$stmt->bindParam(":categorycode", $data["categorycode"],PDO::PARAM_STR);
			$stmt->bindParam(":headcount", $data["headcount"], PDO::PARAM_STR);
			$stmt->bindParam(":dailyrate", $data["dailyrate"], PDO::PARAM_STR);
            $stmt->bindParam(":amount", $data["amount"], PDO::PARAM_STR);
			$stmt->bindParam(":mstatus", $mstatus, PDO::PARAM_STR);
			$stmt->bindParam(":mdate", $data["mdate"], PDO::PARAM_STR);
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

	static public function mdlShowSubmetricsTransactionList($start_date, $end_date, $empid, $mstatus){
		if ($empid != ''){
			$posted_by = " AND (a.postedby = '$empid')";
		}else{
			$posted_by = "";
		}	

		if ($mstatus != ''){
			$status = " AND (a.mstatus = '$mstatus')";
		}else{
			$status = "";
		}

		if(!empty($end_date)){
			$dates = " AND (a.mdate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (a.submetid != '')" . $posted_by . $status . $dates;

		$stmt = (new Connection)->connect()->prepare("SELECT CONCAT(b.lname,', ',b.fname) AS postedby,c.catdescription,a.submetid,a.mdate,a.categorycode,a.headcount,a.dailyrate,a.amount FROM submetrics AS a INNER JOIN employees AS b ON (a.postedby = b.empid) INNER JOIN categoryrawmats AS c ON (a.categorycode = c.categorycode) $whereClause ORDER BY a.mdate");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

    static public function mdlShowSubmetrics($submetid){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM submetrics WHERE (submetid = '$submetid')");

		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}    
}