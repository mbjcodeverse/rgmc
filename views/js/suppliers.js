/*=============================================
EDIT SUPPLIER
=============================================*/
$(".tables").on("click", "tbody .btnEditSupplier", function(){
	var idSupplier = $(this).attr("idSupplier");
	var datum = new FormData();
    datum.append("idSupplier", idSupplier);

    $.ajax({

      url:"ajax/suppliers.ajax.php",
      method: "POST",
      data: datum,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(answer){
      
      	 $("#idSupplier").val(answer["id"]);
	       $("#editSname").val(answer["sname"]);
	       $("#editEmail").val(answer["email"]);
	       $("#editPhone").val(answer["phone"]);
	       $("#editAddress").val(answer["address"]);
         $("#editWebsite").val(answer["website"]);
         $("#editCperson").val(answer["cperson"]);
         $("#editMobile").val(answer["mobile"]);
	  }

  	})

})

/*=============================================
DELETE SUPPLIER
=============================================*/

$(".tables").on("click", "tbody .btnDeleteSupplier", function(){

	var idSupplier = $(this).attr("idSupplier");
	
	swal({
        title: 'Are you sure you want to delete this supplier?',
        text: "If you're not you can cancel the action!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'cancel',
        confirmButtonText: 'Yes, delete supplier!'
      }).then(function(result){
        if (result.value) {
            window.location = "index.php?route=suppliers&idSupplier="+idSupplier;
        }
  })
})