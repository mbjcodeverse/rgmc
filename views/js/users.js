$(function() {
    $(".select").select2({
      minimumResultsForSearch: Infinity,
    });

    $(".select-search").select2();

    disable_checkboxes();
    // $("#branch-selection").hide();
    // $("#branch-gap").show();

    $('#tns-password').passtrength({
  	  minChars: 6,
  	  passwordToggle:true,
      eyeImg :"views/global_assets/images/eye.svg" // toggle icon
    });

	$("#txt-utype").change(function(){
		let user_type = $(this).val();

		if (user_type == 'Super Administrator'){
			on_checkboxes();
			enable_checkboxes();
		}else if (user_type == 'Administrator'){
			on_checkboxes();
			enable_checkboxes();
			$("#chk-ac").prop( "checked", false);
	  	 	$("#chk-ac").val('0');
	  	 	$("#chk-ac").prop( "disabled", true);		
		}else{
			on_checkboxes();
			enable_checkboxes();

		    $("#chk-ac").prop( "checked", false);
	  	 	$("#chk-ac").val('0');
	  	 	$("#chk-ac").prop( "disabled", true);
		}
	}); 

	function on_checkboxes(){
		$('#form-users input[id^="chk"]').prop("checked", true);
		$('#form-users input[id^="chk"]').val('1');
	}	   

	function off_checkboxes(){
		$('#form-users input[id^="chk"]').prop("checked", false);
		$('#form-users input[id^="chk"]').val('0');	  	
	}

	function enable_checkboxes(){
		$('#form-users input[id^="chk"]').attr("disabled", false);
	}	   

	function disable_checkboxes(){
		$('#form-users input[id^="chk"]').attr("disabled", true);  	
	}	

	$(".usersTable").on("click", "tbody .btnEditUser", function(){	
	  var idUser = $(this).attr("idUser");
	  var data = new FormData();
	  data.append("idUser", idUser);
	  $.ajax({
	 	 url:"ajax/get_user_record.ajax.php",
	  	 method: "POST",
	  	 data: data,
	  	 cache: false,
	  	 contentType: false,
	  	 processData: false,
	  	 dataType:"json",
	  	 success:function(answer){
	  	 	$("#num-id").val(answer["id"]);
	  	 	$("#num-idEmployee").val(answer["idEmployee"]).trigger('change');
	  	 	$("#txt-utype").val(answer["utype"]).trigger('change');
	  	 	// $("#tns-branchcode").val(answer["branchcode"]).trigger('change');

	  	 	if (answer["isactive"] == '1'){
	  	 		$("#chk-isactive").prop( "checked", true);
	  	 		$("#chk-isactive").val('1');
	  	 	}else{
	  	 		$("#chk-isactive").prop( "checked", false);
	  	 		$("#chk-isactive").val('0');
	  	 	}
	  	 	
	  	 	$("#txt-lname").val(answer["lname"]);
	  	 	$("#tns-fname").val(answer["fname"]);
	  	 	$("#txt-mi").val(answer["mi"]);

	        $("#sel-gender").val(answer["gender"]).trigger('change');
	        $("#tns-address").val(answer["address"]);
	        $("#num-landline").val(answer["landline"]);
	        $("#num-mobile").val(answer["mobile"]);
	  	 	$("#sel-position").val(answer["idPos"]).trigger('change');
	  	 	$("#num-sssno").val(answer["sssno"]);
	  	 	$("#num-phino").val(answer["phino"]);
	  	 	$("#num-pagibig").val(answer["pagibig"]);
	  	 	$("#num-tin").val(answer["tin"]);
	  	 	$("#sel-estatus").val(answer["estatus"]).trigger('change');
	  	 	$("#txt-posdesc").val(answer["posdesc"]);

	  	 	$("#trans_type").val("Update");
	  	}
	  })
	}); 


	$(".newPics").change(function(){
		var newImage = this.files[0];	
		if (newImage["type"] != "image/jpeg" && newImage["type"] != "image/png"){
			$(".newPics").val("");
			swal.fire({
				type: "error",
				title: "Error uploading image",
				text: "¡Image has to be JPEG or PNG!",
				showConfirmButton: true,
				confirmButtonText: "Close"
			});
		}else if(newImage["size"] > 2000000){
			$(".newPics").val("");
			swal.fire({
				type: "error",
				title: "Error uploading image",
				text: "¡Image too big. It has to be less than 2Mb!",
				showConfirmButton: true,
				confirmButtonText: "Close"
			});
		}else{
			var imgData = new FileReader;
			imgData.readAsDataURL(newImage);
			$(imgData).on("load", function(event){
				var routeImg = event.target.result;
				$(".preview").attr("src", routeImg);
			});
		}
	})

	$(document).on("click", ".btnActivate", function(){
		var idUser = $(this).attr("idUser");
		var userIsactive = $(this).attr("userIsactive");
		var datum = new FormData();

	 	datum.append("activateId", idUser);
	  	datum.append("activateUser", userIsactive);

	  	$.ajax({
		  url:"ajax/users.ajax.php",
		  method: "POST",
		  data: datum,
		  cache: false,
	      contentType: false,
	      processData: false,
	      success: function(answer){
	      	if(answer){
	       	// if(window.matchMedia("(max-width:767px)").matches){
				swal.fire({
	                title: "User status has been successfully updated!",
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
			}
	      }
	  	})

	  	if(userIsactive == 0){
	  		$(this).removeClass('btn-light');	// button class for Activated - users.php
	  		$(this).addClass('btn-danger');	    // button class for Inactive
	  		$(this).html('Deactivated');
	  		$(this).attr('userIsactive',1);

	  	}else{
	  		$(this).addClass('btn-light');
	  		$(this).removeClass('btn-danger');
	  		$(this).html('Activated');
	  		$(this).attr('userIsactive',0);
	  	}
	});


	/*=============================================
	VALIDATE IF USER ALREADY EXISTS
	=============================================*/

	$("#tns-user").change(function(){
		$(".alert").remove();
		var user = $(this).val();
		var data = new FormData();
	 	data.append("validateUser", user);		//users.ajax.php
	  	$.ajax({
		  url:"ajax/users.ajax.php",
		  method: "POST",
		  data: data,
		  cache: false,
	      contentType: false,
	      processData: false,
	      dataType: "json",
	      success: function(answer){ 
	      	if(answer){
		      	swal.fire({
	                title: "User name already exist! Change your entry.",
	                type: "info",
	                showConfirmButton: true,
			        confirmButtonText: "Ok",
			        confirmButtonClass: "btn btn-light btn-lg",
			        allowOutsideClick: false
	                }).then(function(result){
						if (result.value) {
						    $("#tns-user").val('');
							$("#tns-user").focus();
		 				}
	            });
	      	}
	      }
	    });
	});

	$("#tns-password").blur(function(){
		// $(".alert").remove();		//prevent alert box from appearing twice
		var password = $("#tns-password").val();
		var verify = $("#tns-verify").val();
		if((password != '') && (verify != '') && (password != verify)){
		    swal.fire({
                title: "Password detail mismatch! Change entry.",
                type: "warning",
                showConfirmButton: true,
		        confirmButtonText: "Ok",
		        confirmButtonClass: "btn btn-light btn-lg",
		        allowOutsideClick: false
                }).then(function(result){
					if (result.value) {
					    $("#tns-password").val('');
						$("#tns-verify").val('');
						$("#tns-password").focus();
	 				}
            });
	    }
	});

	$("#tns-verify").blur(function(){
		// $(".alert").remove();
		var password = $("#tns-password").val();
		var verify = $("#tns-verify").val();

		if((password != '') && (verify != '') && (password != verify)){
		    swal.fire({
                title: "Password detail mismatch! Change entry.",
                type: "warning",
                showConfirmButton: true,
		        confirmButtonText: "Ok",
		        confirmButtonClass: "btn btn-light btn-lg",
		        allowOutsideClick: false
                }).then(function(result){
					if (result.value) {
					    $("#tns-password").val('');
						$("#tns-verify").val('');
						$("#tns-password").focus();
	 				}
            });			
	    }
	});

	$(".usersTable").on("click", "tbody .btnDeleteUser", function(){
	  var idUser = $(this).attr("idUser");
	  var userPhoto = $(this).attr("userPhoto");  
      swal.fire({
          title: 'Do you want to Cancel purchase order transaction?',
          text: "You won't be able to revert this!",
          type: 'question',
          showCancelButton: true,
          confirmButtonText: 'Yes, Cancel!',
          cancelButtonText: 'No',
          confirmButtonClass: 'btn btn-outline-success',
          cancelButtonClass: 'btn btn-outline-danger',
          allowOutsideClick: false,
          buttonsStyling: false
      }).then(function(result) {
          if(result.value){
			 window.location = "index.php?route=users&idUser="+idUser+"&userPhoto="+userPhoto;
		  }
	  })	  
	})
});




