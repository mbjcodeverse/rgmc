<?php
require_once "connection.php";
class ModelProduct{
	static public function mdlAddProduct($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $prod_id = $pdo->prepare("SELECT CONCAT('P', LPAD((count(id)+1),5,'0')) as gen_id  FROM masterproducts FOR UPDATE");

            $prod_id->execute();
		    $prodid = $prod_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO masterproducts(prodid, prodclass, pdesc, dimension, size, pcolor, categorycode, brandcode, isactive, meas1, eqnum, meas2, eqnum2, meas3, abbr, barcode, ucost, markup, uprice, acost, reorder, purchaseitem, accountcode, vatdesc, remarks, prodname) VALUES (:prodid, :prodclass, :pdesc, :dimension, :size, :pcolor, :categorycode, :brandcode, :isactive, :meas1, :eqnum, :meas2, :eqnum2, :meas3, :abbr, :barcode, :ucost, :markup, :uprice, :acost, :reorder, :purchaseitem, :accountcode, :vatdesc, :remarks, :prodname)");

			$stmt->bindParam(":prodid", $prodid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":prodclass", $data["prodclass"], PDO::PARAM_STR);
			$stmt->bindParam(":pdesc", $data["pdesc"], PDO::PARAM_STR);
			$stmt->bindParam(":dimension", $data["dimension"], PDO::PARAM_STR);
			$stmt->bindParam(":size", $data["size"], PDO::PARAM_STR);
			$stmt->bindParam(":pcolor", $data["pcolor"], PDO::PARAM_STR);
			$stmt->bindParam(":categorycode", $data["categorycode"], PDO::PARAM_STR);
			$stmt->bindParam(":brandcode", $data["brandcode"], PDO::PARAM_STR);
			$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
			$stmt->bindParam(":meas1", $data["meas1"], PDO::PARAM_STR);
			$stmt->bindParam(":eqnum", $data["eqnum"], PDO::PARAM_STR);
			$stmt->bindParam(":meas2", $data["meas2"], PDO::PARAM_STR);
			$stmt->bindParam(":eqnum2", $data["eqnum2"], PDO::PARAM_STR);
			$stmt->bindParam(":meas3", $data["meas3"], PDO::PARAM_STR);
			$stmt->bindParam(":abbr", $data["abbr"], PDO::PARAM_STR);
			$stmt->bindParam(":barcode", $data["barcode"], PDO::PARAM_STR);
			$stmt->bindParam(":ucost", $data["ucost"], PDO::PARAM_STR);
			$stmt->bindParam(":markup", $data["markup"], PDO::PARAM_STR);
			$stmt->bindParam(":uprice", $data["uprice"], PDO::PARAM_STR);
			$stmt->bindParam(":acost", $data["acost"], PDO::PARAM_STR);
			$stmt->bindParam(":reorder", $data["reorder"], PDO::PARAM_STR);
			$stmt->bindParam(":purchaseitem", $data["purchaseitem"], PDO::PARAM_INT);
			$stmt->bindParam(":accountcode", $data["accountcode"], PDO::PARAM_STR);
			$stmt->bindParam(":vatdesc", $data["vatdesc"], PDO::PARAM_STR);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
			$stmt->bindParam(":prodname", $data["prodname"], PDO::PARAM_STR);
			$stmt->execute();


			$branches = $pdo->prepare("SELECT * FROM branch");
            $branches->execute();
		    $branch = $branches -> fetchAll(PDO::FETCH_ASSOC);
		    if(count($branch)!=0){
		    	for($i = 0; $i < count($branch); $i++){
		    	  $branchcode = $branch[$i]['branchcode'];
		    	  $branchprod = $branch[$i]['branchcode'].$prodid[0]['gen_id'];

		    	  $bp = $pdo->prepare("INSERT INTO branchproducts(prodid, isactive, uprice, branchcode, branchprod) VALUES (:prodid, :isactive, :uprice, :branchcode, :branchprod)");

		    	  $bp->bindParam(":prodid", $prodid[0]['gen_id'], PDO::PARAM_STR);
		    	  $bp->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
		    	  $bp->bindParam(":uprice", $data["uprice"], PDO::PARAM_STR);
		    	  $bp->bindParam(":branchcode", $branchcode, PDO::PARAM_STR);
		    	  $bp->bindParam(":branchprod", $branchprod, PDO::PARAM_STR);
		    	  $bp->execute();
		    	}
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

	static public function mdlEditProduct($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE masterproducts SET prodid = :prodid, prodclass = :prodclass, pdesc = :pdesc, dimension = :dimension, size = :size, pcolor = :pcolor, categorycode = :categorycode, brandcode = :brandcode, isactive = :isactive, meas1 = :meas1, eqnum = :eqnum, meas2 = :meas2, eqnum2 = :eqnum2, meas3 = :meas3, abbr = :abbr, barcode = :barcode, ucost = :ucost, markup = :markup, uprice = :uprice, acost = :acost, reorder = :reorder, purchaseitem = :purchaseitem, accountcode = :accountcode, vatdesc = :vatdesc, remarks = :remarks, prodname = :prodname WHERE prodid = :prodid");

			// $stmt->bindParam(":id", $data["id"], PDO::PARAM_INT);
			$stmt->bindParam(":prodid", $data["prodid"], PDO::PARAM_STR);
			$stmt->bindParam(":prodclass", $data["prodclass"], PDO::PARAM_STR);
			$stmt->bindParam(":pdesc", $data["pdesc"], PDO::PARAM_STR);
			$stmt->bindParam(":dimension", $data["dimension"], PDO::PARAM_STR);
			$stmt->bindParam(":size", $data["size"], PDO::PARAM_STR);
			$stmt->bindParam(":pcolor", $data["pcolor"], PDO::PARAM_STR);
			$stmt->bindParam(":categorycode", $data["categorycode"], PDO::PARAM_STR);
			$stmt->bindParam(":brandcode", $data["brandcode"], PDO::PARAM_STR);
			$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
			$stmt->bindParam(":meas1", $data["meas1"], PDO::PARAM_STR);
			$stmt->bindParam(":eqnum", $data["eqnum"], PDO::PARAM_STR);
			$stmt->bindParam(":meas2", $data["meas2"], PDO::PARAM_STR);
			$stmt->bindParam(":eqnum2", $data["eqnum2"], PDO::PARAM_STR);
			$stmt->bindParam(":meas3", $data["meas3"], PDO::PARAM_STR);
			$stmt->bindParam(":abbr", $data["abbr"], PDO::PARAM_STR);
			$stmt->bindParam(":barcode", $data["barcode"], PDO::PARAM_STR);
			$stmt->bindParam(":ucost", $data["ucost"], PDO::PARAM_STR);
			$stmt->bindParam(":markup", $data["markup"], PDO::PARAM_STR);
			$stmt->bindParam(":uprice", $data["uprice"], PDO::PARAM_STR);
			$stmt->bindParam(":acost", $data["acost"], PDO::PARAM_STR);
			$stmt->bindParam(":reorder", $data["reorder"], PDO::PARAM_STR);
			$stmt->bindParam(":purchaseitem", $data["purchaseitem"], PDO::PARAM_INT);
			$stmt->bindParam(":accountcode", $data["accountcode"], PDO::PARAM_STR);
			$stmt->bindParam(":vatdesc", $data["vatdesc"], PDO::PARAM_STR);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
			$stmt->bindParam(":prodname", $data["prodname"], PDO::PARAM_STR);
			$stmt->execute();  

			// $unit_price = str_replace(",","",$data["uprice"]);
			$branch_products = $pdo->prepare("UPDATE branchproducts SET isactive = :isactive, uprice = :uprice WHERE prodid = :prodid");
			$branch_products->bindParam(":prodid", $data["prodid"], PDO::PARAM_STR);
			$branch_products->bindParam(":uprice", $data["uprice"], PDO::PARAM_STR);
			$branch_products->bindParam(":isactive", $data["isactive"], PDO::PARAM_STR);
			$branch_products->execute();

		    $pdo->commit();
		    return "ok";
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}	
		$pdo = null;	
		$stmt = null;
	}

    // GET Master Product
	static public function mdlShowTransactionProduct($prodid){
		$stmt = (new Connection)->connect()->prepare("SELECT a.prodid,b.catdescription,IFNULL(d.brandname,'') AS brandname,a.prodname,a.ucost,a.markup,a.uprice,a.reorder,a.vatdesc,IFNULL(c.accountdesc,'') AS accountdesc FROM masterproducts AS a INNER JOIN category AS b ON (a.categorycode = b.categorycode) LEFT JOIN prodaccount AS c ON (a.accountcode = c.accountcode) LEFT JOIN brand AS d ON (a.brandcode = d.brandcode) WHERE (a.prodid = '$prodid')");
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

    // GET Branch Product
	static public function mdlShowTransactionBranchProduct($prodid,$branchcode){
		$stmt = (new Connection)->connect()->prepare("SELECT a.prodid,b.catdescription,IFNULL(d.brandname,'') AS brandname,a.prodname,a.ucost,a.markup,e.uprice,a.reorder,a.vatdesc,IFNULL(c.accountdesc,'') AS accountdesc FROM masterproducts AS a INNER JOIN category AS b ON (a.categorycode = b.categorycode) LEFT JOIN prodaccount AS c ON (a.accountcode = c.accountcode) LEFT JOIN brand AS d ON (a.brandcode = d.brandcode) INNER JOIN branchproducts AS e ON (a.prodid = e.prodid) WHERE (a.prodid = '$prodid') AND (e.branchcode = '$branchcode')");
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlShowProduct($item, $value){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM masterproducts WHERE $item = :$item");
		$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlShowProductList($categorycode, $brandcode, $accountcode, $meas1, $vatdesc){
		if ($categorycode != ''){
			$category = " AND (a.categorycode = '$categorycode')";
		}else{
			$category = "";
		}

		if ($brandcode != ''){
			$brand = " AND (a.brandcode = '$brandcode')";
		}else{
			$brand = "";
		}

		if ($accountcode != ''){
			$account = " AND (a.accountcode = '$accountcode')";
		}else{
			$account = "";
		}

		if ($meas1 != ''){
			$meas = " AND (a.meas1 = '$meas1')";
		}else{
			$meas = "";
		}

		if ($vatdesc != ''){
			$vat = " AND (a.vatdesc = '$vatdesc')";
		}else{
			$vat = "";
		}											

		$whereClause = "WHERE (a.prodid != '')" . $category . $brand . $account . $meas . $vat;

		$stmt = (new Connection)->connect()->prepare("SELECT a.prodid,b.catdescription,IFNULL(d.brandname,'') AS brandname,a.prodname,a.ucost,a.markup,a.uprice,a.reorder,IFNULL(c.accountdesc,'') AS accountdesc FROM masterproducts AS a INNER JOIN category AS b ON (a.categorycode = b.categorycode) LEFT JOIN prodaccount AS c ON (a.accountcode = c.accountcode) LEFT JOIN brand AS d ON (a.brandcode = d.brandcode) $whereClause ORDER BY d.brandname,a.pdesc");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

	// CONCAT(IFNULL(c.brandname,''),' ',a.pdesc) AS prodname

	static public function mdlShowProductSalesList($branchcode){
		if ($branchcode != ''){
		   $stmt = (new Connection)->connect()->prepare("SELECT a.prodid,IFNULL(b.brandname,'') AS brandname,a.prodname,a.ucost,a.markup,c.uprice FROM masterproducts AS a LEFT JOIN brand AS b ON (a.brandcode = b.brandcode) INNER JOIN branchproducts AS c ON (a.prodid = c.prodid) WHERE (c.branchcode = '$branchcode') AND (c.isactive = 1) ORDER BY b.brandname,a.pdesc");			
			
		}else{
		   $stmt = (new Connection)->connect()->prepare("SELECT a.prodid,IFNULL(b.brandname,'') AS brandname,a.prodname,a.ucost,a.markup,a.uprice FROM masterproducts AS a LEFT JOIN brand AS b ON (a.brandcode = b.brandcode) WHERE (a.isactive = 1) ORDER BY b.brandname,a.pdesc");			
		}

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	











	static public function mdl_Show_Product_Sales_List($branchcode){
		if ($branchcode != ''){
		   $stmt = (new Connection)->connect()->prepare("SELECT a.prodid,TRIM(CONCAT(IFNULL(b.brandname,''),' ',a.prodname)) AS pdesc,a.prodname,a.ucost,a.markup,c.uprice FROM masterproducts AS a LEFT JOIN brand AS b ON (a.brandcode = b.brandcode) INNER JOIN branchproducts AS c ON (a.prodid = c.prodid) WHERE (c.branchcode = '$branchcode') AND (c.isactive = 1) ORDER BY pdesc");			
			
		}else{
		   $stmt = (new Connection)->connect()->prepare("SELECT a.prodid,TRIM(CONCAT(IFNULL(b.brandname,''),' ',a.prodname)) AS pdesc,a.prodname,a.ucost,a.markup,a.uprice FROM masterproducts AS a LEFT JOIN brand AS b ON (a.brandcode = b.brandcode) WHERE (a.isactive = 1) ORDER BY pdesc");			
		}

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}		











    
    // PURCHASE ITEM Master Products
	static public function mdlShowPurchaseItemProducts(){
		$stmt = (new Connection)->connect()->prepare("SELECT a.id,a.prodid,IFNULL(b.brandname,'') AS brandname,a.prodname,a.ucost,a.markup,a.uprice,a.reorder,c.accountdesc FROM masterproducts AS a INNER JOIN prodaccount AS c ON (a.accountcode = c.accountcode) LEFT JOIN brand AS b ON (a.brandcode = b.brandcode) WHERE (a.isactive = 1) AND (a.purchaseitem = 1) ORDER BY b.brandname,a.pdesc");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}


	static public function mdlShowBranchProducts($branchcode){
		$stmt = (new Connection)->connect()->prepare("SELECT a.prodid,c.catdescription,IFNULL(e.brandname,'') AS brandname,a.prodname,a.ucost,a.markup,b.uprice,a.reorder,b.isactive,IFNULL(d.accountdesc,'') AS accountdesc FROM masterproducts AS a INNER JOIN branchproducts AS b ON (b.prodid = a.prodid) INNER JOIN category AS c ON (a.categorycode = c.categorycode) LEFT JOIN prodaccount AS d ON (a.accountcode = d.accountcode) LEFT JOIN brand AS e ON (a.brandcode = e.brandcode) WHERE (b.branchcode = '$branchcode') ORDER BY e.brandname,a.pdesc");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlUpdateBranchProduct($uprice,$isactive,$branchprod){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $unit_price = str_replace(",","",$uprice);

			$stmt = $pdo->prepare("UPDATE branchproducts SET uprice = :uprice, isactive = :isactive WHERE branchprod = :branchprod");

			$stmt->bindParam(":uprice", $unit_price, PDO::PARAM_STR);
			$stmt->bindParam(":isactive", $isactive, PDO::PARAM_STR);
			$stmt->bindParam(":branchprod", $branchprod, PDO::PARAM_STR);
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
}