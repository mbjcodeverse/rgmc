$('.productsTable').DataTable({
	ajax: "ajax/productlist.ajax.php",
	autoWidth: true,
    scrollY: 409,
    pagelength: 25,
    lengthMenu: [[25, 50, -1], [25, 50, "All"]],
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            language: {
                search: '<span>Filter:</span> _INPUT_',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
            }
});

//Set the focus on Datatable search box
$("div.dataTables_filter input").focus();

$(".productsTable tbody").on("click", "button.addProduct", function(){
	var idProduct = $(this).attr("idProduct");
	
	$(this).removeClass("btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct");
	$(this).addClass("btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2");

	var data = new FormData();
    data.append("idProduct", idProduct);
     $.ajax({
     	url:"ajax/products.ajax.php",	
      	method: "POST",
      	data: data,
      	cache: false,
      	contentType: false,
      	processData: false,
      	dataType:"json",
      	success:function(answer){
      	   var pdesc = answer["pdesc"];
           var refcode = $("#txtRefcode").val();

	       $(".enlisted_products").append(
          	'<div class="row no-gutter no-gutter-row" style="margin-left:5px;margin-right:5px;">'+   /*top-bottom, lef-right*/
			  '<!-- 	Description 	-->'+
	          '<div class="input-group input-group-sm mb-3 col-sm-8" style="padding-right:0px">'+
	              '<span class="input-group-addon"><button type="button" class="btn btn-sm btn-light waves-effect waves-light m-1 removeProduct" idProduct="'+idProduct+'"><i class="fa fa-minus"></i></button></span>'+
	              '<input type="text" class="form-control txtPdesc" refcode="'+refcode+'" idProduct="'+idProduct+'" name="addProduct" value="'+pdesc+'" readonly required>'+
	          '</div>'+	          

	          '<!-- 	Quantity 	-->'+
	          '<div class="input-group col-sm-2 enterQty">'+
	             '<input type="text" class="form-control numQty" idProduct="'+idProduct+'" name="newQty" value="" placeholder="0" required>'+
	          '</div>' +
	        '</div>')
	        // listProducts();
	        $('.numQty').focus();
	        //-------------------------------------------------------------------
      	}
     })

 //     <div class="col-lg-10">
	// 	<div class="input-group">
	// 		<input type="text" class="form-control" placeholder="Right button">
	// 		<span class="input-group-append">
	// 			<button class="btn btn-light" type="button">Button</button>
	// 		</span>
	// 	</div>
	// </div>
});

/*=============================================
          Remove Product from Table
=============================================*/
var idRemoveProduct = [];
localStorage.removeItem("removeProduct");
$(".product-list-form").on("click", "button.removeProduct", function(){
	console.log("$(this)", $(this));
	$(this).parent().parent().parent().remove();
	console.log("idProduct", idProduct);
	var idProduct = $(this).attr("idProduct");

	if(localStorage.getItem("removeProduct") == null){
		idRemoveProduct = [];
	}else{
		idRemoveProduct.concat(localStorage.getItem("removeProduct"))
	}

	idRemoveProduct.push({"idProduct":idProduct});
	localStorage.setItem("removeProduct", JSON.stringify(idRemoveProduct));

	$("button.recoverButton[idProduct='"+idProduct+"']").removeClass('btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2');
	$("button.recoverButton[idProduct='"+idProduct+"']").addClass('btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct');
	
	// if all product has been removed
	if($(".enlisted_products").children().length == 0){
		$(".saveButton").prop('disabled', true);
	}else{
        // listMaterials();
	}
})

$(".product-list-form").on("change keypress keyup blur", "input.numQty", function(){
	$(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57) && (event.which != 8)) {
            event.preventDefault();
        }else{
		    listMaterials();
    }
})

$(".productsTable").on("draw.dt", function(){
	if(localStorage.getItem("removeProduct") != null){
		var listIdProducts = JSON.parse(localStorage.getItem("removeProduct"));
		for(var i = 0; i < listIdProducts.length; i++){
			$("button.recoverButton[idProduct='"+listIdProducts[i]["idProduct"]+"']").removeClass('btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2');
			$("button.recoverButton[idProduct='"+listIdProducts[i]["idProduct"]+"']").addClass('tn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct');
		}
	}
})

// Deactivate ADD product button after loading material on the list
function removeAddedProducts(){
	var idProduct = $(".removeProduct");     
	var tableButtons = $(".productsTable tbody button.addProduct");
	for(var i = 0; i < idProduct.length; i++){
		var button = $(idProduct[i]).attr("idProduct");
		for(var j = 0; j < tableButtons.length; j ++){
			if($(tableButtons[j]).attr("idProduct") == button){
				$(tableButtons[j]).removeClass("tn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct");
				$(tableButtons[j]).addClass("btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2");
			}
		}
	}
}

$('.productsTable').on( 'draw.dt', function(){
	removeAddedProducts();
})
