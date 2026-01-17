<?php
class ControllerUsers{
	/*=============================================
	USER LOGIN
	=============================================*/
	static public function ctrShowUserType(){
		$answer = (new UsersModel)->mdlShowUserType();
		return $answer;
	}

	static public function ctrUserLogin(){
		if (isset($_POST["loginUser"])) {
			if (preg_match('/^[a-zA-Z0-9]+$/', $_POST["loginUser"]) && 
				preg_match('/^[a-zA-Z0-9]+$/', $_POST["loginPass"])) {
				$encryptpass = crypt($_POST["loginPass"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
				$table = 'users';
				$item = 'user';
				$value = $_POST["loginUser"];
				$answer = (new UsersModel)->MdlShowUsers($table, $item, $value);
				// var_dump($answer);
				if(!empty($answer) && $answer["user"] == $_POST["loginUser"] && $answer["password"] == $encryptpass){
					if($answer["isactive"] == 1){
						$_SESSION["loggedIn"] = "ok";
						$_SESSION["id"] = $answer["id"];
						$_SESSION["empid"] = $answer["empid"];
						$_SESSION["user"] = $answer["user"];
						$_SESSION["userid"] = $answer["userid"];
						$_SESSION["photo"] = $answer["photo"];
						$_SESSION["utype"] = $answer["utype"];

						$_SESSION["mt"] = $answer["mt"];
						$_SESSION["ins"] = $answer["ins"];
						$_SESSION["po"] = $answer["po"];
						$_SESSION["inc"] = $answer["inc"];
						$_SESSION["rel"] = $answer["rel"];
						$_SESSION["ret"] = $answer["ret"];
						$_SESSION["adj"] = $answer["adj"];
						$_SESSION["inv"] = $answer["inv"];
						$_SESSION["rep"] = $answer["rep"];
						$_SESSION["su"] = $answer["su"];
						$_SESSION["em"] = $answer["em"];
						$_SESSION["bd"] = $answer["bd"];
						$_SESSION["prt"] = $answer["prt"];
						$_SESSION["cat"] = $answer["cat"];
						$_SESSION["bnd"] = $answer["bnd"];
						$_SESSION["mac"] = $answer["mac"];
						$_SESSION["cls"] = $answer["cls"];
						$_SESSION["ac"] = $answer["ac"];

						echo '<script>
								 window.location = "default";
							  </script>';
					}else{
						echo '<br><div style="text-align:center;" class="alert alert-danger">User is deactivated</div>';
					}
				}else{
					echo '<br><div style="text-align:center;" class="alert alert-danger">User or password incorrect</div>';
				}
			}
		}
	}

	static public function ctrCreateUser(){
		if (isset($_POST["tns-user"])&&isset($_POST["tns-password"])&&($_POST["trans_type"] == 'New')) {
				if (isset($_POST['num-isactive'])){
				    $isactive=$_POST['num-isactive'];
			    }else{
			    	$isactive="0";
			    }
				/*=============================================
				VALIDATE IMAGE
				=============================================*/

				$photo = "";
				if(isset($_FILES["tns-photo"]["tmp_name"]) && $_FILES["tns-photo"]["size"] != 0){
					list($width, $height) = getimagesize($_FILES["tns-photo"]["tmp_name"]);
					$newWidth = 500;
					$newHeight = 500;
					/*=============================================
					Let's create the folder for each user
					=============================================*/
					$next_id = (new Connection)->connect()->query("SHOW TABLE STATUS LIKE 'users'")->fetch(PDO::FETCH_ASSOC)['Auto_increment'];

					$folder = "views/img/users/U".$next_id;
					mkdir($folder, 0755);

					/*=============================================
					PHP functions depending on the image
					=============================================*/
					if($_FILES["tns-photo"]["type"] == "image/jpeg"){
						$randomNumber = mt_rand(100,999);
						$photo = "views/img/users/U".$next_id."/".$randomNumber.".jpg";
						$srcImage = imagecreatefromjpeg($_FILES["tns-photo"]["tmp_name"]);
						$destination = imagecreatetruecolor($newWidth, $newHeight);
						imagecopyresized($destination, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
						imagejpeg($destination, $photo);
					}
					if ($_FILES["tns-photo"]["type"] == "image/png") {
						$randomNumber = mt_rand(100,999);
						$photo = "views/img/users/U".$next_id."/".$randomNumber.".png";
						// $photo = "views/img/users/".$_POST["tns-user"]."/".$randomNumber.".png";
						$srcImage = imagecreatefrompng($_FILES["tns-photo"]["tmp_name"]);
						$destination = imagecreatetruecolor($newWidth, $newHeight);
						imagecopyresized($destination, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
						imagepng($destination, $photo);
					}
				}

				if (isset($_POST['chk-mt'])){
				    $mt=$_POST['chk-mt'];
			    }else{
			    	$mt="0";
			    }

				if (isset($_POST['chk-ins'])){
				    $ins=$_POST['chk-ins'];
			    }else{
			    	$ins="0";
			    }

				if (isset($_POST['chk-po'])){
				    $po=$_POST['chk-po'];
			    }else{
			    	$po="0";
			    }

				if (isset($_POST['chk-inc'])){
				    $inc=$_POST['chk-inc'];
			    }else{
			    	$inc="0";
			    }

				if (isset($_POST['chk-rel'])){
				    $rel=$_POST['chk-rel'];
			    }else{
			    	$rel="0";
			    }			    			    			    

				if (isset($_POST['chk-ret'])){
				    $ret=$_POST['chk-ret'];
			    }else{
			    	$ret="0";
			    }

				if (isset($_POST['chk-adj'])){
				    $adj=$_POST['chk-adj'];
			    }else{
			    	$adj="0";
			    }

				if (isset($_POST['chk-inv'])){
				    $inv=$_POST['chk-inv'];
			    }else{
			    	$inv="0";
			    }

				if (isset($_POST['chk-rep'])){
				    $rep=$_POST['chk-rep'];
			    }else{
			    	$rep="0";
			    }			    			    

				if (isset($_POST['chk-su'])){
				    $su=$_POST['chk-su'];
			    }else{
			    	$su="0";
			    }

				if (isset($_POST['chk-em'])){
				    $em=$_POST['chk-em'];
			    }else{
			    	$em="0";
			    }

			    if (isset($_POST['chk-bd'])){
				    $bd=$_POST['chk-bd'];
			    }else{
			    	$bd="0";
			    }	

			    if (isset($_POST['chk-prt'])){
				    $prt=$_POST['chk-prt'];
			    }else{
			    	$prt="0";
			    }

			    if (isset($_POST['chk-cat'])){
				    $cat=$_POST['chk-cat'];
			    }else{
			    	$cat="0";
			    }

			    if (isset($_POST['chk-bnd'])){
				    $bnd=$_POST['chk-bnd'];
			    }else{
			    	$bnd="0";
			    }

			    if (isset($_POST['chk-mac'])){
				    $mac=$_POST['chk-mac'];
			    }else{
			    	$mac="0";
			    }

			    if (isset($_POST['chk-cls'])){
				    $cls=$_POST['chk-cls'];
			    }else{
			    	$cls="0";
			    }

			    if (isset($_POST['chk-ac'])){
				    $ac=$_POST['chk-ac'];
			    }else{
			    	$ac="0";
			    }

			    $table = 'users';
				$encryptpass = crypt($_POST["tns-password"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

				$data = array('empid' => $_POST["tns-empid"],
							  'user' => $_POST["tns-user"],
							  'password' => $encryptpass,
							  'override' => $_POST["tns-override"],
							  'utype' => $_POST["txt-utype"],
							  'photo' => $photo,
							  'isactive' => $isactive,
							  'userid' => $_POST["txt-userid"],
							  'mt' => $mt,
							  'ins' => $ins,
							  'po' => $po,
							  'inc' => $inc,
							  'rel' => $rel,
							  'ret' => $ret,
							  'adj' => $adj,
							  'inv' => $inv,
							  'rep' => $rep,
							  'su' => $su,
							  'em' => $em,
							  'bd' => $bd,
							  'prt' => $prt,
							  'cat' => $cat,
							  'bnd' => $bnd,
							  'mac' => $mac,
							  'cls' => $cls,
							  'ac' => $ac);

				$answer = (new UsersModel)->mdlAddUser($table, $data);
				if ($answer == 'ok') {
						echo '<script>
							swal.fire({
				                title: "User access has been successfully appended!",
				                type: "success",
				                showConfirmButton: true,
						        confirmButtonText: "Ok",
						        confirmButtonClass: "btn btn-light btn-lg",
						        allowOutsideClick: false
				                }).then(function(result){
										if (result.value) {
										  window.location = "users";
						 				}
			                });
						</script>';
				}
		}
	}

	/*=============================================
	SHOW USER
	=============================================*/
	static public function ctrShowUsers($item, $value){
		$table = "users";
		$answer = (new UsersModel)->MdlShowUsers($table, $item, $value);
		return $answer;
	}

	/*=============================================
	EDIT USER
	=============================================*/
	static public function ctrEditUser(){
		if (isset($_POST["tns-user"])&&isset($_POST["tns-password"])&&($_POST["trans_type"] == 'Update')) {
			if (preg_match('/^[a-zA-Z0-9ñÑ \.\,\-\(\)\&\:\@\#\$\%\^\*\_\+\=\"\;\?\<\>\/\!\`\{\}]+$/', $_POST["num-idEmployee"]) &&
				preg_match('/^[a-zA-Z0-9ñÑ \.\,\-\(\)\&\:\@\#\$\%\^\*\_\+\=\"\;\?\<\>\/\!\`\{\}]+$/', $_POST["tns-user"]) &&
				preg_match('/^[a-zA-Z0-9ñÑ \.\,\-\(\)\&\:\@\#\$\%\^\*\_\+\=\"\;\?\<\>\/\!\`\{\}]+$/', $_POST["tns-password"])){

				if (isset($_POST['num-isactive'])){
				    $isactive=$_POST['num-isactive'];
			    }else{
			    	$isactive="0";
			    }
				/*=============================================
				VALIDATE IMAGE
				=============================================*/
				$photo = $_POST["currentImage"];
				if(isset($_FILES["tns-photo"]["tmp_name"]) && !empty($_FILES["tns-photo"]["tmp_name"])){
					list($width, $height) = getimagesize($_FILES["tns-photo"]["tmp_name"]);
					$newWidth = 500;
					$newHeight = 500;
					/*=============================================
					Let's create the folder for each user
					=============================================*/
					$folder = "views/img/users/U".$_POST["idUser"];
					/*=============================================
					we ask first if there's an existing image in the database
					=============================================*/
					if(!empty($_POST["currentImage"]) && $_POST["currentImage"] != "views/img/users/default/anonymous.png"){
						unlink($_POST["currentImage"]);
					}else{
						mkdir($folder, 0755);	
					}
					/*=============================================
					PHP functions depending on the image
					=============================================*/
					if($_FILES["tns-photo"]["type"] == "image/jpeg"){
						/*We save the image in the folder*/
						$randomNumber = mt_rand(100,999);
						$photo = "views/img/users/U".$_POST["idUser"]."/".$randomNumber.".jpg";
						$srcImage = imagecreatefromjpeg($_FILES["tns-photo"]["tmp_name"]);
						$destination = imagecreatetruecolor($newWidth, $newHeight);
						imagecopyresized($destination, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
						imagejpeg($destination, $photo);
					}
					if ($_FILES["tns-photo"]["type"] == "image/png") {
						/*We save the image in the folder*/
						$randomNumber = mt_rand(100,999);
						$photo = "views/img/users/U".$_POST["idUser"]."/".$randomNumber.".png";
						$srcImage = imagecreatefrompng($_FILES["tns-photo"]["tmp_name"]);
						$destination = imagecreatetruecolor($newWidth, $newHeight);
						imagecopyresized($destination, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
						imagepng($destination, $photo);
					}
				}

				if (isset($_POST['chk-mt'])){
				    $mt=$_POST['chk-mt'];
			    }else{
			    	$mt="0";
			    }

				if (isset($_POST['chk-ins'])){
				    $ins=$_POST['chk-ins'];
			    }else{
			    	$ins="0";
			    }

				if (isset($_POST['chk-po'])){
				    $po=$_POST['chk-po'];
			    }else{
			    	$po="0";
			    }

				if (isset($_POST['chk-inc'])){
				    $inc=$_POST['chk-inc'];
			    }else{
			    	$inc="0";
			    }

				if (isset($_POST['chk-rel'])){
				    $rel=$_POST['chk-rel'];
			    }else{
			    	$rel="0";
			    }			    			    			    

				if (isset($_POST['chk-ret'])){
				    $ret=$_POST['chk-ret'];
			    }else{
			    	$ret="0";
			    }

				if (isset($_POST['chk-adj'])){
				    $adj=$_POST['chk-adj'];
			    }else{
			    	$adj="0";
			    }

				if (isset($_POST['chk-inv'])){
				    $inv=$_POST['chk-inv'];
			    }else{
			    	$inv="0";
			    }

				if (isset($_POST['chk-rep'])){
				    $rep=$_POST['chk-rep'];
			    }else{
			    	$rep="0";
			    }			    			    

				if (isset($_POST['chk-su'])){
				    $su=$_POST['chk-su'];
			    }else{
			    	$su="0";
			    }

				if (isset($_POST['chk-em'])){
				    $em=$_POST['chk-em'];
			    }else{
			    	$em="0";
			    }

			    if (isset($_POST['chk-bd'])){
				    $bd=$_POST['chk-bd'];
			    }else{
			    	$bd="0";
			    }	

			    if (isset($_POST['chk-prt'])){
				    $prt=$_POST['chk-prt'];
			    }else{
			    	$prt="0";
			    }

			    if (isset($_POST['chk-cat'])){
				    $cat=$_POST['chk-cat'];
			    }else{
			    	$cat="0";
			    }

			    if (isset($_POST['chk-bnd'])){
				    $bnd=$_POST['chk-bnd'];
			    }else{
			    	$bnd="0";
			    }

			    if (isset($_POST['chk-mac'])){
				    $mac=$_POST['chk-mac'];
			    }else{
			    	$mac="0";
			    }

			    if (isset($_POST['chk-cls'])){
				    $cls=$_POST['chk-cls'];
			    }else{
			    	$cls="0";
			    }

			    if (isset($_POST['chk-ac'])){
				    $ac=$_POST['chk-ac'];
			    }else{
			    	$ac="0";
			    }

				$table = 'users';
				$encryptpass = crypt($_POST["tns-password"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');		

				$data = array("id" => $_POST["idUser"],
                              'empid' => $_POST["tns-empid"],
							  'user' => $_POST["tns-user"],
							  'password' => $encryptpass,
							  'override' => $_POST["tns-override"],
							  'utype' => $_POST["txt-utype"],
							  'photo' => $photo,
							  'isactive' => $isactive,
							  'userid' => $_POST["txt-userid"],
							  'mt' => $mt,
							  'ins' => $ins,
							  'po' => $po,
							  'inc' => $inc,
							  'rel' => $rel,
							  'ret' => $ret,
							  'adj' => $adj,
							  'inv' => $inv,
							  'rep' => $rep,
							  'su' => $su,
							  'em' => $em,
							  'bd' => $bd,
							  'prt' => $prt,
							  'cat' => $cat,
							  'bnd' => $bnd,
							  'mac' => $mac,
							  'cls' => $cls,
							  'ac' => $ac);

				$answer = (new UsersModel)->mdlEditUser($table, $data);
				if ($answer == 'ok') {
					echo '<script>
							swal.fire({
				                title: "User access has been successfully updated!",
				                type: "success",
				                showConfirmButton: true,
						        confirmButtonText: "Ok",
						        confirmButtonClass: "btn btn-light btn-lg",
						        allowOutsideClick: false
				                }).then(function(result){
										if (result.value) {
										  window.location = "users";
						 				}
			                });
					</script>';
				}
				else{
					echo '<script>
						swal.fire({
			                title: "User name and Password should not contain quotation marks!",
			                type: "success",
			                showConfirmButton: true,
					        confirmButtonText: "Ok",
					        confirmButtonClass: "btn btn-light btn-lg",
					        allowOutsideClick: false
			                }).then(function(result){
									if (result.value) {
									  window.location = "users";
					 				}
		                });
					</script>';
				}
			}	
		}
	}

	/*=============================================
	DELETE USER
	=============================================*/
	static public function ctrDeleteUser(){
		if(isset($_GET["idUser"])){
			$table ="users";
			$data = $_GET["idUser"];

			if($_GET["userPhoto"] != "" && $_GET["userPhoto"] != "views/img/users/default/anonymous.png"){
				unlink($_GET["userPhoto"]);
				rmdir('views/img/users/U'.$_GET["idUser"]);
			}

			// if($_GET["userPhoto"] != ""){
			// 	unlink($_GET["userPhoto"]);				
			// 	rmdir('views/img/users/'.$_GET["username"]);
			// }
			$answer = (new UsersModel)->mdlDeleteUser($table, $data);
			if($answer == "ok"){
				echo'<script>
					swal.fire({
		                title: "User access has been successfully deleted!",
		                type: "success",
		                showConfirmButton: true,
				        confirmButtonText: "Ok",
				        confirmButtonClass: "btn btn-light btn-lg",
				        allowOutsideClick: false
		                }).then(function(result){
								if (result.value) {
								  window.location = "users";
				 				}
	                });
				</script>';
			}		
		}
	}

	/*=============================================
	GET OVERRIDE KEY
	=============================================*/
	static public function ctrGetOverrideKey($override_key){
		$answer = (new UsersModel)->mdlGetOverrideKey($override_key);
		return $answer;
	}	
}
