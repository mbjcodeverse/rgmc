<?php
require_once "connection.php";
class ModelGoods{
	static public function mdlAddGoods($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $item_id = $pdo->prepare("SELECT CONCAT(LPAD((count(id)+1),5,'0')) as gen_id  FROM products FOR UPDATE");

            $item_id->execute();
		    $itemid = $item_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO products(itemid, pdesc, categorycode, isactive, meas2, eqnum, meas1, itemcode, ucost, reorder, pweight, wmeas, remarks) VALUES (:itemid, :pdesc, :categorycode, :isactive, :meas2, :eqnum, :meas1, :itemcode, :ucost, :reorder, :pweight, :wmeas, :remarks)");

			$stmt->bindParam(":itemid", $itemid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":pdesc", $data["pdesc"], PDO::PARAM_STR);
			$stmt->bindParam(":categorycode", $data["categorycode"], PDO::PARAM_STR);
			$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
			$stmt->bindParam(":meas1", $data["meas1"], PDO::PARAM_STR);
			$stmt->bindParam(":eqnum", $data["eqnum"], PDO::PARAM_STR);
			$stmt->bindParam(":meas2", $data["meas2"], PDO::PARAM_STR);
			$stmt->bindParam(":itemcode", $data["itemcode"], PDO::PARAM_STR);
			$stmt->bindParam(":ucost", $data["ucost"], PDO::PARAM_STR);
			$stmt->bindParam(":reorder", $data["reorder"], PDO::PARAM_STR);
			$stmt->bindParam(":pweight", $data["pweight"], PDO::PARAM_STR);
			$stmt->bindParam(":wmeas", $data["wmeas"], PDO::PARAM_STR);
			// $stmt->bindParam(":purchaseitem", $data["purchaseitem"], PDO::PARAM_INT);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
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

	static public function mdlEditGoods($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE products SET itemid = :itemid, pdesc = :pdesc, categorycode = :categorycode, isactive = :isactive, meas2 = :meas2, eqnum = :eqnum, meas1 = :meas1, itemcode = :itemcode, ucost = :ucost, reorder = :reorder, pweight = :pweight, wmeas = :wmeas, remarks = :remarks WHERE itemid = :itemid");

			$stmt->bindParam(":itemid", $data["itemid"], PDO::PARAM_STR);
			$stmt->bindParam(":pdesc", $data["pdesc"], PDO::PARAM_STR);
			$stmt->bindParam(":categorycode", $data["categorycode"], PDO::PARAM_STR);
			$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
			$stmt->bindParam(":meas1", $data["meas1"], PDO::PARAM_STR);
			$stmt->bindParam(":eqnum", $data["eqnum"], PDO::PARAM_STR);
			$stmt->bindParam(":meas2", $data["meas2"], PDO::PARAM_STR);
			$stmt->bindParam(":itemcode", $data["itemcode"], PDO::PARAM_STR);
			$stmt->bindParam(":ucost", $data["ucost"], PDO::PARAM_STR);
			$stmt->bindParam(":reorder", $data["reorder"], PDO::PARAM_STR);
			$stmt->bindParam(":pweight", $data["pweight"], PDO::PARAM_STR);
			$stmt->bindParam(":wmeas", $data["wmeas"], PDO::PARAM_STR);
			// $stmt->bindParam(":purchaseitem", $data["purchaseitem"], PDO::PARAM_INT);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
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

	static public function mdlShowAllGoods(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM products ORDER BY pdesc");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}		

	static public function mdlShowGoodsList($categorycode){
		if ($categorycode != ''){
			$category = " AND (a.categorycode = '$categorycode')";
		}else{
			$category = "";
		}										

		$whereClause = "WHERE (a.itemid != '')" . $category;

		$stmt = (new Connection)->connect()->prepare("SELECT a.itemid,a.itemcode,b.catdescription,a.pdesc,a.ucost FROM products AS a INNER JOIN categorygoods AS b ON (a.categorycode = b.categorycode) $whereClause ORDER BY a.pdesc");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlShowGood($item, $value){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM products WHERE $item = :$item");
		$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlShowPurchaseGoodProducts(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM products WHERE (isactive = 1) ORDER BY pdesc");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlShowTransactionGood($itemid){
		$stmt = (new Connection)->connect()->prepare("SELECT a.itemid,a.itemcode,b.catdescription,a.pdesc,a.ucost,a.meas2,a.reorder FROM products AS a INNER JOIN categorygoods AS b ON (a.categorycode = b.categorycode) WHERE (a.itemid = '$itemid')");
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}	

    // Note: Incoming SQL -> c.qty*a.eqnum as qty -> for eqnum > 1.00 (e.g. ROLL - MTR)
	// static public function mdlShowProdStocks($itemid){
	// 	$stmt = (new Connection)->connect()->prepare("
	// 		  SELECT a.itemid,b.invdate AS tdate,b.invnumber AS tcode,a.itemcode,a.meas1,a.eqnum,a.meas2,'Inventory' AS details,a.pdesc,CONCAT(e.fname,' ',e.lname) AS transinfo,c.qty,1 AS priority FROM items AS a INNER JOIN inventoryitems AS c ON (a.itemid = c.itemid) INNER JOIN inventory AS b ON (c.invnumber = b.invnumber) INNER JOIN employees AS e ON (b.postedby = e.empid) WHERE (b.invstatus = 'Posted') AND (b.invdate >= '2022-11-14') AND (a.itemid = '$itemid') 
    //           UNION ALL
    //           SELECT a.itemid,b.deldate AS tdate,b.delnumber AS tcode,a.itemcode,a.meas1,a.eqnum,a.meas2,'Incoming' AS details,a.pdesc,CONCAT(e.fname,' ',e.lname) AS transinfo,c.qty*a.eqnum as qty,1 AS priority FROM items AS a INNER JOIN incomingitems AS c ON (a.itemid = c.itemid) INNER JOIN incoming AS b ON (c.delnumber = b.delnumber) INNER JOIN employees AS e ON (b.postedby = e.empid) WHERE (b.delstatus = 'Posted') AND (b.deldate >= '2022-11-14') AND (a.itemid = '$itemid') 
	// 		  UNION ALL
    //           SELECT a.itemid,b.retdate AS tdate,b.retnumber AS tcode,a.itemcode,a.meas1,a.eqnum,a.meas2,'Return' AS details,a.pdesc,CONCAT(e.fname,' ',e.lname) AS transinfo,c.qty*a.eqnum as qty,1 AS priority FROM items AS a INNER JOIN returnitems AS c ON (a.itemid = c.itemid) INNER JOIN returned AS b ON (c.retnumber = b.retnumber) INNER JOIN employees AS e ON (b.postedby = e.empid) WHERE (b.retstatus = 'Posted') AND (b.retdate >= '2022-11-14') AND (a.itemid = '$itemid')
    //           UNION ALL
    //           SELECT a.itemid,b.reqdate AS tdate,b.reqnumber AS tcode,a.itemcode,a.meas1,a.eqnum,a.meas2,'Withdrawal' AS details,a.pdesc,CONCAT(e.fname,' ',e.lname) AS transinfo,c.qty,1 AS priority FROM items AS a INNER JOIN stockoutitems AS c ON (a.itemid = c.itemid) INNER JOIN stockout AS b ON (c.reqnumber = b.reqnumber) INNER JOIN employees AS e ON (b.requestby = e.empid) WHERE (b.reqstatus = 'Posted') AND (b.reqdate >= '2022-11-14') AND (a.itemid = '$itemid') ORDER BY itemid,tdate,priority");

	// 	$stmt -> execute();
	// 	return $stmt -> fetchAll();
	// 	$stmt -> close();
	// 	$stmt = null;
	// }		
	
	// static public function mdlGoodReport($reptype, $filter, $categorycode){
	// 	if ($categorycode != ''){
	// 		$category = " AND (a.categorycode = '$categorycode')";
	// 	}else{
	// 		$category = "";
	// 	}	
		
	// 	if ($filter == 1){
	// 		$fil_ter = '';
	// 	}elseif ($filter == 2){
	// 		$fil_ter = 'AND (a.onhand > a.reorder)';
	// 	}elseif ($filter == 3){
	// 		$fil_ter = 'AND (a.onhand <= a.reorder)';
	// 	}elseif ($filter == 4){
	// 		$fil_ter = 'AND (a.onhand < 0.00)';
	// 	}

	// 	$whereClause = "WHERE (a.itemid != '')" . $category . $fil_ter;
	// 	$whereClause2 = "WHERE (a.itemid != '') AND (a.eqnum > 1.00)" . $category . $fil_ter;

	// 	if ($reptype == 1){
	// 		$stmt = (new Connection)->connect()->prepare("SELECT a.itemid,a.itemcode,b.catdescription,a.pdesc,a.meas1,a.eqnum,a.meas2,a.ucost,a.reorder,a.onhand FROM items AS a INNER JOIN category AS b ON (a.categorycode = b.categorycode) $whereClause ORDER BY b.catdescription, a.pdesc");
	// 	}elseif ($reptype == 2){
	// 		$stmt = (new Connection)->connect()->prepare("SELECT a.itemid,a.itemcode,b.catdescription,a.pdesc,a.meas1,a.eqnum,a.meas2,a.ucost,a.reorder,a.onhand FROM items AS a INNER JOIN category AS b ON (a.categorycode = b.categorycode) $whereClause ORDER BY a.pdesc");		
	// 	}else{
	// 		$stmt = (new Connection)->connect()->prepare("SELECT a.itemid,a.itemcode,b.catdescription,a.pdesc,a.meas1,a.eqnum,a.meas2,a.ucost,a.reorder,a.onhand FROM items AS a INNER JOIN category AS b ON (a.categorycode = b.categorycode) $whereClause2 ORDER BY a.pdesc");
	// 	}

	// 	$stmt -> execute();
	// 	return $stmt -> fetchAll();
	// 	$stmt -> close();
	// 	$stmt = null;
	// }	
}