<?php
require_once "connection.php";
class ModelUserRights{
	static public function mdlAddUserRights($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $user_id = $pdo->prepare("SELECT userid FROM userrights ORDER BY id DESC LIMIT 1");

            $user_id->execute();
		    $userid = $user_id -> fetchAll(PDO::FETCH_ASSOC);

		    $user_number = $userid[0]['userid'];
		    $sequence_code = strval(intval(substr($user_number,-4)) + 1);
		    $usercode = 'U' . str_repeat("0",4 - strlen($sequence_code)) . $sequence_code;

			// $encryptpass = crypt($data["upassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
			$encryptpass = $data["upassword"];
			$stmt = $pdo->prepare("INSERT INTO userrights(userid, empid, utype, ulevel, username, upassword,
                                                                 mmd, mip, mfp, mpc, mrm,
                                                                 mmr, md, mret, mir, minv,
                                                                 mrep, mirm, mifp, mcrm, mcfg, mopr,
                                                                 tmd, tmt, tmi, tpo, tis,
                                                                 trel, tret, tadj, tinv, trep,
                                                                 tprt, tcat, tbr, tmac, tcls,
                                                                 psup, pemp, paccess, plog, pcost)
                                                         VALUES (:userid, :empid, :utype, :ulevel, :username, :upassword,
                                                                 :mmd, :mip, :mfp, :mpc, :mrm,
                                                                 :mmr, :md, :mret, :mir, :minv,
                                                                 :mrep, :mirm, :mifp, :mcrm, :mcfg, :mopr,
                                                                 :tmd, :tmt, :tmi, :tpo, :tis,
                                                                 :trel, :tret, :tadj, :tinv, :trep,
                                                                 :tprt, :tcat, :tbr, :tmac, :tcls,
                                                                 :psup, :pemp, :paccess, :plog, :pcost)");	

			$stmt->bindParam(":userid", $usercode, PDO::PARAM_STR);
			$stmt->bindParam(":empid", $data["empid"], PDO::PARAM_STR);
            $stmt->bindParam(":utype", $data["utype"], PDO::PARAM_STR);
			$stmt->bindParam(":ulevel", $data["ulevel"], PDO::PARAM_STR);
            $stmt->bindParam(":username", $data["username"], PDO::PARAM_STR);
            $stmt->bindParam(":upassword", $encryptpass, PDO::PARAM_STR);

			$stmt->bindParam(":mmd", $data["mmd"], PDO::PARAM_STR);	
			$stmt->bindParam(":mip", $data["mip"], PDO::PARAM_STR);
			$stmt->bindParam(":mfp", $data["mfp"], PDO::PARAM_STR);
			$stmt->bindParam(":mpc", $data["mpc"], PDO::PARAM_STR);
			$stmt->bindParam(":mrm", $data["mrm"], PDO::PARAM_STR);
			$stmt->bindParam(":mmr", $data["mmr"], PDO::PARAM_STR);	
			$stmt->bindParam(":md", $data["md"], PDO::PARAM_STR);
            $stmt->bindParam(":mret", $data["mret"], PDO::PARAM_STR);
            $stmt->bindParam(":mir", $data["mir"], PDO::PARAM_STR);
            $stmt->bindParam(":minv", $data["minv"], PDO::PARAM_STR);
            $stmt->bindParam(":mrep", $data["mrep"], PDO::PARAM_STR);
            $stmt->bindParam(":mirm", $data["mirm"], PDO::PARAM_STR);
            $stmt->bindParam(":mifp", $data["mifp"], PDO::PARAM_STR);
            $stmt->bindParam(":mcrm", $data["mcrm"], PDO::PARAM_STR);
            $stmt->bindParam(":mcfg", $data["mcfg"], PDO::PARAM_STR);
			$stmt->bindParam(":mopr", $data["mopr"], PDO::PARAM_STR);

            $stmt->bindParam(":tmd", $data["tmd"], PDO::PARAM_STR);
            $stmt->bindParam(":tmt", $data["tmt"], PDO::PARAM_STR);
            $stmt->bindParam(":tmi", $data["tmi"], PDO::PARAM_STR);
            $stmt->bindParam(":tpo", $data["tpo"], PDO::PARAM_STR);
            $stmt->bindParam(":tis", $data["tis"], PDO::PARAM_STR);
            $stmt->bindParam(":trel", $data["trel"], PDO::PARAM_STR);
            $stmt->bindParam(":tret", $data["tret"], PDO::PARAM_STR);
            $stmt->bindParam(":tadj", $data["tadj"], PDO::PARAM_STR);
            $stmt->bindParam(":tinv", $data["tinv"], PDO::PARAM_STR);
            $stmt->bindParam(":trep", $data["trep"], PDO::PARAM_STR);
            $stmt->bindParam(":tprt", $data["tprt"], PDO::PARAM_STR);
            $stmt->bindParam(":tcat", $data["tcat"], PDO::PARAM_STR);
            $stmt->bindParam(":tbr", $data["tbr"], PDO::PARAM_STR);
            $stmt->bindParam(":tmac", $data["tmac"], PDO::PARAM_STR);
            $stmt->bindParam(":tcls", $data["tcls"], PDO::PARAM_STR);

            $stmt->bindParam(":psup", $data["psup"], PDO::PARAM_STR);
            $stmt->bindParam(":pemp", $data["pemp"], PDO::PARAM_STR);
            $stmt->bindParam(":paccess", $data["paccess"], PDO::PARAM_STR);
			$stmt->bindParam(":plog", $data["plog"], PDO::PARAM_STR);
            $stmt->bindParam(":pcost", $data["pcost"], PDO::PARAM_STR);
			$stmt->execute();	
		    $pdo->commit();

		    return $usercode;
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}
	}

    static public function mdlEditUserRights($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE userrights SET
                        userid = :userid, empid = :empid, utype = :utype, ulevel = :ulevel,
                        mmd = :mmd, mip = :mip, mfp = :mfp, mpc = :mpc, mrm = :mrm,
                        mmr = :mmr, md = :md, mret = :mret, mir = :mir, minv = :minv,
                        mrep = :mrep, mirm = :mirm, mifp = :mifp, mcrm = :mcrm, mcfg = :mcfg, mopr = :mopr,
                        tmd = :tmd, tmt = :tmt, tmi = :tmi, tpo = :tpo, tis = :tis,
                        trel = :trel, tret = :tret, tadj = :tadj, tinv = :tinv, trep = :trep,
                        tprt = :tprt, tcat = :tcat, tbr = :tbr, tmac = :tmac, tcls = :tcls,
                        psup = :psup, pemp = :pemp, paccess = :paccess, plog = :plog, pcost = :pcost
                        WHERE userid = :userid");

            $stmt->bindParam(":userid", $data["userid"], PDO::PARAM_STR);
			$stmt->bindParam(":empid", $data["empid"], PDO::PARAM_STR);
            $stmt->bindParam(":utype", $data["utype"], PDO::PARAM_STR);
			$stmt->bindParam(":ulevel", $data["ulevel"], PDO::PARAM_STR);

			$stmt->bindParam(":mmd", $data["mmd"], PDO::PARAM_STR);	
			$stmt->bindParam(":mip", $data["mip"], PDO::PARAM_STR);
			$stmt->bindParam(":mfp", $data["mfp"], PDO::PARAM_STR);
			$stmt->bindParam(":mpc", $data["mpc"], PDO::PARAM_STR);
			$stmt->bindParam(":mrm", $data["mrm"], PDO::PARAM_STR);
			$stmt->bindParam(":mmr", $data["mmr"], PDO::PARAM_STR);	
			$stmt->bindParam(":md", $data["md"], PDO::PARAM_STR);
            $stmt->bindParam(":mret", $data["mret"], PDO::PARAM_STR);
            $stmt->bindParam(":mir", $data["mir"], PDO::PARAM_STR);
            $stmt->bindParam(":minv", $data["minv"], PDO::PARAM_STR);
            $stmt->bindParam(":mrep", $data["mrep"], PDO::PARAM_STR);
            $stmt->bindParam(":mirm", $data["mirm"], PDO::PARAM_STR);
            $stmt->bindParam(":mifp", $data["mifp"], PDO::PARAM_STR);
            $stmt->bindParam(":mcrm", $data["mcrm"], PDO::PARAM_STR);
            $stmt->bindParam(":mcfg", $data["mcfg"], PDO::PARAM_STR);
			$stmt->bindParam(":mopr", $data["mopr"], PDO::PARAM_STR);

            $stmt->bindParam(":tmd", $data["tmd"], PDO::PARAM_STR);
            $stmt->bindParam(":tmt", $data["tmt"], PDO::PARAM_STR);
            $stmt->bindParam(":tmi", $data["tmi"], PDO::PARAM_STR);
            $stmt->bindParam(":tpo", $data["tpo"], PDO::PARAM_STR);
            $stmt->bindParam(":tis", $data["tis"], PDO::PARAM_STR);
            $stmt->bindParam(":trel", $data["trel"], PDO::PARAM_STR);
            $stmt->bindParam(":tret", $data["tret"], PDO::PARAM_STR);
            $stmt->bindParam(":tadj", $data["tadj"], PDO::PARAM_STR);
            $stmt->bindParam(":tinv", $data["tinv"], PDO::PARAM_STR);
            $stmt->bindParam(":trep", $data["trep"], PDO::PARAM_STR);
            $stmt->bindParam(":tprt", $data["tprt"], PDO::PARAM_STR);
            $stmt->bindParam(":tcat", $data["tcat"], PDO::PARAM_STR);
            $stmt->bindParam(":tbr", $data["tbr"], PDO::PARAM_STR);
            $stmt->bindParam(":tmac", $data["tmac"], PDO::PARAM_STR);
            $stmt->bindParam(":tcls", $data["tcls"], PDO::PARAM_STR);

            $stmt->bindParam(":psup", $data["psup"], PDO::PARAM_STR);
            $stmt->bindParam(":pemp", $data["pemp"], PDO::PARAM_STR);
            $stmt->bindParam(":paccess", $data["paccess"], PDO::PARAM_STR);
			$stmt->bindParam(":plog", $data["plog"], PDO::PARAM_STR);
            $stmt->bindParam(":pcost", $data["pcost"], PDO::PARAM_STR);
			$stmt->execute();

			$userid = $data["userid"];
		    $pdo->commit();
		    return $userid;
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}
	}

    static public function mdlResetAccount($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			// $encryptpass = crypt($data["password"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
			$encryptpass = $data["password"];

			$stmt = $pdo->prepare("UPDATE userrights SET username = :username, upassword = :upassword WHERE userid = :userid");

            $stmt->bindParam(":userid", $data["userid"], PDO::PARAM_STR);
			$stmt->bindParam(":username", $data["username"], PDO::PARAM_STR);
			$stmt->bindParam(":upassword", $encryptpass, PDO::PARAM_STR);	
			$stmt->execute();

			$userid = $data["userid"];
		    $pdo->commit();
		    return $userid;
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}
	}	

    static public function mdlShowUserList(){
		$stmt = (new Connection)->connect()->prepare("SELECT a.id,a.empid,a.lname,a.fname,a.mi,b.positiondesc,c.userid,c.utype FROM employees AS a INNER JOIN position AS b ON (a.idPos = b.id) INNER JOIN userrights AS c ON (a.empid = c.empid) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
	}
    
    static public function mdlShowUserRights($item, $value){
		if($item != null){
			$stmt = (new Connection)->connect()->prepare("SELECT * FROM userrights WHERE $item = :$item");
			$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetch();
		}else{
			$stmt = (new Connection)->connect()->prepare("SELECT * FROM userrights ORDER BY userid");
			$stmt -> execute();
			return $stmt -> fetchAll();
		}
	}

	static public function mdlGetUserCredentials($tableUsers, $item, $value){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM $tableUsers WHERE $item = :$item");
		$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
	}

	static public function mdlGetUserLogin($username, $upassword){
		// $encryptpass = crypt($upassword, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
		$encryptpass = $upassword;
		$stmt = (new Connection)->connect()->prepare("SELECT userid, username, upassword FROM userrights WHERE (username = '$username') AND (upassword = '$encryptpass')");
		$stmt -> execute();
		return $stmt -> fetch();
	}

	static public function mdlAddLogin($empid){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			date_default_timezone_set('Asia/Manila');

			$emp_id = $empid;
			$currentdate = date('Y-m-d');
			$currenttime = date('h:i A');
			$currentday = date("l");
			$stmt = (new Connection)->connect()->prepare("INSERT INTO logintracker(empid, cdate, ctime, cday) VALUES (:empid, :cdate, :ctime, :cday)");
			$stmt -> bindParam(":empid", $emp_id, PDO::PARAM_STR);
			$stmt -> bindParam(":cdate", $currentdate, PDO::PARAM_STR);
			$stmt -> bindParam(":ctime", $currenttime, PDO::PARAM_STR);
			$stmt -> bindParam(":cday", $currentday, PDO::PARAM_STR);
			
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

	static public function mdlShowLoginReport($start_date, $end_date){
		if(!empty($end_date)){
			$dates = " AND (c.cdate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (c.empid != 'EM00001')" . $dates;
        
		$stmt = (new Connection)->connect()->prepare("SELECT c.cdate,c.ctime,c.cday,CONCAT(a.fname,' ',a.lname) AS full_name FROM employees AS a INNER JOIN logintracker AS c ON (a.empid = c.empid) $whereClause ORDER BY c.cdate,c.id");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}		

	static public function mdlUserSwitchView($empid, $utype){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM userrights WHERE (empid = '$empid') AND (utype = '$utype')");
		$stmt -> execute();
		return $stmt -> fetch();
	}
}