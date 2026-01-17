<?php
require_once "connection.php";
class ModelProductmetrics{
	static public function mdlAddProductmetrics($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $item_id = $pdo->prepare("SELECT CONCAT('PM',LPAD((count(id)+1),5,'0')) as gen_id  FROM productmetrics FOR UPDATE");

            $item_id->execute();
		    $prodmetid = $item_id -> fetchAll(PDO::FETCH_ASSOC);

            $mstatus = 'Posted';
			$stmt = $pdo->prepare("INSERT INTO productmetrics(prodmetid, categorycode, headcount, dailyrate, amount, mstatus, mdate, postedby)
													         VALUES (:prodmetid, :categorycode, :headcount, :dailyrate, :amount, :mstatus, :mdate, :postedby)");

			$stmt->bindParam(":prodmetid", $prodmetid[0]['gen_id'], PDO::PARAM_STR);
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

	static public function mdlEditProductmetrics($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
            $mstatus = 'Posted';
			$stmt = $pdo->prepare("UPDATE productmetrics SET prodmetid = :prodmetid,
																	categorycode = :categorycode,
																	headcount = :headcount,
																	dailyrate = :dailyrate,
																	amount = :amount,
																	mstatus = :mstatus,
																	mdate = :mdate
													WHERE prodmetid = :prodmetid");

			$stmt->bindParam(":prodmetid", $data["prodmetid"], PDO::PARAM_STR);
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

	static public function mdlShowProductmetricsTransactionList($start_date, $end_date, $empid, $mstatus){
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

		$whereClause = "WHERE (a.prodmetid != '')" . $posted_by . $status . $dates;

		$stmt = (new Connection)->connect()->prepare("SELECT CONCAT(b.lname,', ',b.fname) AS postedby,c.catdescription,a.prodmetid,a.mdate,a.categorycode,a.headcount,a.dailyrate,a.amount FROM productmetrics AS a INNER JOIN employees AS b ON (a.postedby = b.empid) INNER JOIN categorygoods AS c ON (a.categorycode = c.categorycode) $whereClause ORDER BY a.mdate");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

    static public function mdlShowProductmetrics($prodmetid){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM productmetrics WHERE (prodmetid = '$prodmetid')");

		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}    
}