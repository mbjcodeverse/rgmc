<?php
class ControllerUserRights{
	static public function ctrAddUserRights($data){
		$answer = (new ModelUserRights)->mdlAddUserRights($data);
	 	return $answer;
    }

	// Update EXISTING RECORD
	static public function ctrEditUserRights($data){
		$answer = (new ModelUserRights)->mdlEditUserRights($data);
		return $answer;
	}   

	static public function ctrResetAccount($data){
		$answer = (new ModelUserRights)->mdlResetAccount($data);
		return $answer;
	} 	
    
    static public function ctrShowUserList(){
		$answer = (new ModelUserRights)->mdlShowUserList();
		return $answer;
	}	

    static public function ctrShowUserRights($item, $value){
		$answer = (new ModelUserRights)->mdlShowUserRights($item, $value);
		return $answer;
	}

	static public function ctrUserLogin(){
		if (isset($_POST["loginUser"])) {
				// $encryptpass = crypt($_POST["loginPass"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
				$encryptpass = $_POST["loginPass"];
				$table = 'userrights';
				$item = 'username';
				$value = $_POST["loginUser"];
				$answer = (new ModelUserRights)->mdlGetUserCredentials($table, $item, $value);

				if(!empty($answer) && $answer["username"] == $_POST["loginUser"] && $answer["upassword"] == $encryptpass){
					$_SESSION["loggedIn"] = "ok";
					$_SESSION["id"] = $answer["id"];

					// $_SESSION["username"] = $answer["username"];
					// $_SESSION["upassword"] = $answer["upassword"];
					
					$_SESSION["empid"] = $answer["empid"];
					$_SESSION["userid"] = $answer["userid"];
                    $_SESSION["utype"] = $answer["utype"];
					$_SESSION["ulevel"] = $answer["ulevel"];
                    $_SESSION["photo"] = $answer["photo"];
					
					$_SESSION["mmd"] = $answer["mmd"];
					$_SESSION["mip"] = $answer["mip"];
					$_SESSION["mfp"] = $answer["mfp"];
					$_SESSION["mpc"] = $answer["mpc"];
					$_SESSION["mrm"] = $answer["mrm"];
					$_SESSION["mmr"] = $answer["mmr"];
					$_SESSION["md"] = $answer["md"];
					$_SESSION["mret"] = $answer["mret"];
					$_SESSION["mir"] = $answer["mir"];
					$_SESSION["minv"] = $answer["minv"];
					$_SESSION["mrep"] = $answer["mrep"];
                    $_SESSION["mirm"] = $answer["mirm"];
                    $_SESSION["mifp"] = $answer["mifp"];
                    $_SESSION["mcrm"] = $answer["mcrm"];
                    $_SESSION["mcfg"] = $answer["mcfg"];
					$_SESSION["mopr"] = $answer["mopr"];

					$_SESSION["tmd"] = $answer["tmd"];
					$_SESSION["tmt"] = $answer["tmt"];
					$_SESSION["tmi"] = $answer["tmi"];
					$_SESSION["tpo"] = $answer["tpo"];
					$_SESSION["tis"] = $answer["tis"];
					$_SESSION["trel"] = $answer["trel"];
					$_SESSION["tret"] = $answer["tret"];
					$_SESSION["tadj"] = $answer["tadj"];
					$_SESSION["tinv"] = $answer["tinv"];
					$_SESSION["trep"] = $answer["trep"];
					$_SESSION["tprt"] = $answer["tprt"];
                    $_SESSION["tcat"] = $answer["tcat"];
                    $_SESSION["tbr"] = $answer["tbr"];
                    $_SESSION["tmac"] = $answer["tmac"];
                    $_SESSION["tcls"] = $answer["tcls"];  
                    
                    $_SESSION["psup"] = $answer["psup"];
                    $_SESSION["pemp"] = $answer["pemp"];
                    $_SESSION["paccess"] = $answer["paccess"];  
					$_SESSION["plog"] = $answer["plog"];
                    $_SESSION["pcost"] = $answer["pcost"]; 

					$empid = $_SESSION["empid"];
					$answer = (new ModelUserRights)->mdlAddLogin($empid);
				    if ($answer == 'ok') {
						if ($_SESSION["ulevel"] == "Operator"){
							echo '<script>
									window.location = "prodoperator";
								</script>';					
						}elseif ($_SESSION["tmd"] == "Full"){
							// $_SESSION["show_dashboard"] = true;
							echo '<script>
									window.location = "home";
								</script>';
						}elseif ($_SESSION["mmd"] == "Full"){
							// $_SESSION["show_dashboard"] = false;
							echo '<script>
									window.location = "factorydashboard";
								</script>';
						}else{
							// $_SESSION["show_dashboard"] = false;
							echo '<script>
									window.location = "default";
								</script>';
						}
				    }
				}else{
					echo '<br><div style="text-align:center;" class="alert alert-danger">User or password incorrect</div>';
				}
			
		}
	}

	static public function ctrGetUserLogin($username, $upassword){
		$answer = (new ModelUserRights)->mdlGetUserLogin($username, $upassword);
		return $answer;
	}  

	static public function ctrShowLoginReport($start_date, $end_date){
		$answer = (new ModelUserRights)->mdlShowLoginReport($start_date, $end_date);
		return $answer;
	}

	static public function ctrUserSwitchView(){
		if (isset($_POST["loginEmpid"]) && isset($_POST["loginUtype"])) {
			$empid = $_POST["loginEmpid"];
			$utype = $_POST["loginUtype"];

			if ($utype == 'Technical'){
				$utype = 'Manufacturing';
			}else{
				$utype = 'Technical';
			}

			$answer = (new ModelUserRights)->mdlUserSwitchView($empid, $utype);
			if(!empty($answer)){
				$_SESSION["loggedIn"] = "ok";
				$_SESSION["id"] = $answer["id"];

				// $_SESSION["username"] = $answer["username"];
				// $_SESSION["upassword"] = $answer["upassword"];
				
				$_SESSION["empid"] = $answer["empid"];
				$_SESSION["userid"] = $answer["userid"];
				$_SESSION["utype"] = $answer["utype"];
				$_SESSION["ulevel"] = $answer["ulevel"];
				$_SESSION["photo"] = $answer["photo"];
				
				$_SESSION["mmd"] = $answer["mmd"];
				$_SESSION["mip"] = $answer["mip"];
				$_SESSION["mfp"] = $answer["mfp"];
				$_SESSION["mpc"] = $answer["mpc"];
				$_SESSION["mrm"] = $answer["mrm"];
				$_SESSION["mmr"] = $answer["mmr"];
				$_SESSION["md"] = $answer["md"];
				$_SESSION["mret"] = $answer["mret"];
				$_SESSION["mir"] = $answer["mir"];
				$_SESSION["minv"] = $answer["minv"];
				$_SESSION["mrep"] = $answer["mrep"];
				$_SESSION["mirm"] = $answer["mirm"];
				$_SESSION["mifp"] = $answer["mifp"];
				$_SESSION["mcrm"] = $answer["mcrm"];
				$_SESSION["mcfg"] = $answer["mcfg"];

				$_SESSION["tmd"] = $answer["tmd"];
				$_SESSION["tmt"] = $answer["tmt"];
				$_SESSION["tmi"] = $answer["tmi"];
				$_SESSION["tpo"] = $answer["tpo"];
				$_SESSION["tis"] = $answer["tis"];
				$_SESSION["trel"] = $answer["trel"];
				$_SESSION["tret"] = $answer["tret"];
				$_SESSION["tadj"] = $answer["tadj"];
				$_SESSION["tinv"] = $answer["tinv"];
				$_SESSION["trep"] = $answer["trep"];
				$_SESSION["tprt"] = $answer["tprt"];
				$_SESSION["tcat"] = $answer["tcat"];
				$_SESSION["tbr"] = $answer["tbr"];
				$_SESSION["tmac"] = $answer["tmac"];
				$_SESSION["tcls"] = $answer["tcls"];  
				
				$_SESSION["psup"] = $answer["psup"];
				$_SESSION["pemp"] = $answer["pemp"];
				$_SESSION["paccess"] = $answer["paccess"];  
				$_SESSION["plog"] = $answer["plog"];
				$_SESSION["pcost"] = $answer["pcost"];

				if ($_SESSION["tmd"] == "Full"){
					echo '<script>
							window.location = "home";
						</script>';
				}else{
					echo '<script>
							window.location = "factorydashboard";
						</script>';
				}
			}
		}
	}
}