if (!$.fn.DataTable.isDataTable('.branchProductsTable')) {
  var bp = $('.branchProductsTable').DataTable({
      deferRender: true,
      processing: true,
      autoWidth: true,
      scrollY: 360,
      pagelength: 25,
      lengthMenu: [[25, 50], [25, 50]],
      dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
              language: {
                  loadingRecords: 'Please wait - loading...',
                  processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
                  search: '<span>Filter:</span> _INPUT_',
                  searchPlaceholder: 'Type to filter...',
                  lengthMenu: '<span>Show:</span> _MENU_',
                  paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
              }
  });
}

if (!$.fn.DataTable.isDataTable('.productTable')) {
  var pr = $('.productTable').DataTable({
      deferRender: true,
      processing: true,
      autoWidth: true,
      scrollY: 360,
      pagelength: 25,
      lengthMenu: [[25, 50], [25, 50]],
      dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
              language: {
                  loadingRecords: 'Please wait - loading...',
                  processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
                  search: '<span>Filter:</span> _INPUT_',
                  searchPlaceholder: 'Type to filter...',
                  lengthMenu: '<span>Show:</span> _MENU_',
                  paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
              }
  });
}

$(function() {
   $(".select").select2({
      minimumResultsForSearch: Infinity,
   });

   $(".select-search").select2();

   $('#product-form input[id^="num"]').on("keypress", function (e) {
      return _helper.isNumericDash(e) ? true : e.preventDefault();
   });

   $('#product-form input[id^="txt"]').on("keypress", function (e) {
      return _helper.isString(e) ? true : e.preventDefault();
   });

   $('#product-form input[id^="tns"]').on("keypress", function (e) {
      return _helper.allChars(e) ? true : e.preventDefault();
   });   

   $("#btn-new").click(function(){
     $('#txt-prodid').val('');
     $('#tns-prodclass').val('');
     $('#tns-pdesc').val('');
     $('#tns-dimension').val('');
     $("#sel-size").val('').trigger('change');
     $("#sel-pcolor").val('').trigger('change');
     $("#sel-categorycode").val('').trigger('change');
     $("#sel-brandcode").val('0').trigger('change');
     $("#chk-isactive").prop( "checked", true); 
     $("#sel-meas1").val('').trigger('change');
     $('#num-eqnum').val('1.00');
     $("#sel-meas2").val('').trigger('change');
     $('#num-eqnum2').val('0.00');
     $("#sel-meas3").val('').trigger('change');     
     $('#tns-abbr').val('');
     $('#tns-barcode').val('');
     $('#num-ucost').val('0.00');
     $('#num-markup').val('0.00');
     $('#num-uprice').val('0.00');
     $('#num-acost').val('0.00');
     $('#num-reorder').val('0.00');
     $("#chk-purchaseitem").prop( "checked", true);
     $("#sel-accountcode").val('').trigger('change');
     $("#sel-vatdesc").val('').trigger('change');
     $('#tns-remarks').val(''); 

     $('#trans_type').val('New');
   }); 

   $("#lbl-sel-size").click(function(){
     $("#sel-size").val('').trigger('change');
   });  

   $("#lbl-sel-pcolor").click(function(){
     $("#sel-pcolor").val('').trigger('change');
   });

   $("#lbl-sel-brandcode").click(function(){
     $("#sel-brandcode").val('').trigger('change');
   });

   $("#lbl-lst-brandcode").click(function(){
     $("#lst-brandcode").val('').trigger('change');
   });

   $("#lbl-lst-categorycode").click(function(){
     $("#lst-categorycode").val('').trigger('change');
   }); 

   $("#lbl-lst-accountcode").click(function(){
     $("#lst-accountcode").val('').trigger('change');
   }); 

   $("#lbl-lst-meas1").click(function(){
     $("#lst-meas1").val('').trigger('change');
   });  

   $("#lbl-lst-vatdesc").click(function(){
     $("#lst-vatdesc").val('').trigger('change');
   });           

   $("#lbl-sel-accountcode").click(function(){
     $("#sel-accountcode").val('').trigger('change');
   });      

   $("#lbl-tns-dimension").click(function(){
     $("#tns-dimension").val('');
   }); 

   $("#lbl-tns-prodclass").click(function(){
     $("#tns-prodclass").val('');
   });       

   $("#lbl-sel-meas2,#lbl-num-eqnum").click(function(){
     $("#sel-meas2").val('').trigger('change');
     $('#num-eqnum').val('1.00');
   });

   $("#lbl-sel-meas3,#lbl-num-eqnum2").click(function(){
     $("#sel-meas3").val('').trigger('change');
     $('#num-eqnum2').val('0.00');
   });            

   $("#btn-branch-products").click(function(){
     window.location = 'productbranch';
   });

   $("#btn-master-products").click(function(){
     window.location = 'product';
   });   

   $('#num-ucost, #num-uprice').on("change keyup", function(){
      let num_ucost = $('#num-ucost').val();
      let num_uprice = $('#num-uprice').val();
      let ucost = parseFloat(num_ucost.replace(",",""));
      let price = parseFloat(num_uprice.replace(",",""));
      let markup = price - ucost;
      $('#num-markup').val(numberWithCommas(markup.toFixed(2)));
   });  

   // Save Product Details
   $(".product-form").submit(function (e) {
      e.preventDefault();

      if ($('#trans_type').val() == 'New'){
          var title = "Do you want to new product details?";
          var text = "Product shall be [ POPULATED ] in all branches.";
      }else{
          var title = "Do you want to update product details?";
          var text = "Product changes shall be [ CASCADED ] in all branches.";
      }

      swal.fire({
          title: title,
          text: text,
          type: 'question',
          showCancelButton: true,
          confirmButtonText: 'Yes, Save it!',
          cancelButtonText: 'Cancel!',
          confirmButtonClass: 'btn btn-outline-success',
          cancelButtonClass: 'btn btn-outline-danger',
          allowOutsideClick: false,
          buttonsStyling: false
      }).then(function(result) {
          if(result.value) {
            var trans_type = $("#trans_type").val();

            var prodid = $("#txt-prodid").val();
            var prodclass = $("#tns-prodclass").val();
            var pdesc = $("#tns-pdesc").val();
            var dimension = $("#tns-dimension").val();
            var size = $("#sel-size").val();
            var pcolor = $("#sel-pcolor").val();
            var categorycode = $("#sel-categorycode").val();
            var brandcode = $("#sel-brandcode").val();

            if ($('#chk-isactive').prop('checked')){
              var isactive = "1";
            }else{
              var isactive = "0";
            }

            var meas1 = $("#sel-meas1").val();

            var txt_eqnum = $("#num-eqnum").val();
            var eqnum = parseFloat(txt_eqnum.replaceAll(",",""));

            var meas2 = $("#sel-meas2").val();

            var txt_eqnum2 = $("#num-eqnum2").val();
            var eqnum2 = parseFloat(txt_eqnum2.replaceAll(",",""));

            var meas3 = $("#sel-meas3").val();
            var abbr = $("#tns-abbr").val();
            var barcode = $("#tns-barcode").val();

            var txt_ucost = $("#num-ucost").val();
            var ucost = parseFloat(txt_ucost.replaceAll(",",""));

            var txt_markup = $("#num-markup").val();
            var markup = parseFloat(txt_markup.replaceAll(",",""));

            var txt_uprice = $("#num-uprice").val();
            var uprice = parseFloat(txt_uprice.replaceAll(",",""));

            var txt_acost = $("#num-acost").val();
            var acost = parseFloat(txt_acost.replaceAll(",","")); 

            var txt_reorder = $("#num-reorder").val();
            var reorder = parseFloat(txt_reorder.replaceAll(",",""));  

            if ($('#chk-purchaseitem').prop('checked')){
              var purchaseitem = "1";
            }else{
              var purchaseitem = "0";
            }

            // var purchaseitem = $("#chk-purchaseitem").val();
            var accountcode = $("#sel-accountcode").val();
            var vatdesc = $("#sel-vatdesc").val();
            var remarks = $("#tns-remarks").val();  

            // Joined - Product Name
            let eq_num = numberWithCommas(txt_eqnum).split(".");
            let eq_num2 = numberWithCommas(txt_eqnum2).split(".");                   

            var p_class = (prodclass != '') ? prodclass + ' ': '';
            var p_desc = pdesc + ' ';
            var p_dimen = (dimension != '') ? dimension + ' ': '';
            var p_size = (size != '') ? size + ' ': '';
            var p_color = (pcolor != '') ? pcolor + ' ': '';

            if ((eq_num[0] != "1")&&(eq_num[1] == "00")){
              var p_eqnum = eq_num[0] + meas2 + ' ';
            }else if ((eq_num[0] == "1")&&(eq_num[1] == "00")){
              var p_eqnum = '';
            }else if ((eq_num[0] == "0")&&(eq_num[1] != "00")&&(eq_num[1] != "50")&&(eq_num[1] != "25")){
              var p_eqnum = eq_num[0] + meas2 + ' ';
            }else if ((eq_num[0] == "0")&&(eq_num[1] == "50")){
              var p_eqnum = '1/2 ';
            }else if ((eq_num[0] == "0")&&(eq_num[1] == "25")){
              var p_eqnum = '1/4 ';
            }else{
              var p_eqnum = '';
            }

            if (meas3 != ''){
              if ((eq_num2[0] != "1")&&(eq_num2[1] == "00")){
                var p_eqnum2 = '/ ' + eq_num2[0] + meas3 + ' ';
              }else if ((eq_num2[0] == "1")&&(eq_num2[1] == "00")){
                var p_eqnum2 = '';
              }else if ((eq_num2[0] == "0")&&(eq_num2[1] != "00")){
                var p_eqnum2 = '/ ' + eq_num2[0] + meas3 + ' ';
              }
            }else{
              var p_eqnum2 = '';
            }                  
            var prodname = p_class + p_desc + p_color + p_dimen + p_size + p_eqnum + p_eqnum2 + '(' + meas1.toUpperCase() + ')';

            // Send data to Ajax
            var product = new FormData();
            product.append("trans_type", trans_type);

            product.append("prodid", prodid);
            product.append("prodclass", prodclass);
            product.append("pdesc", pdesc);
            product.append("dimension", dimension);
            product.append("size", size);
            product.append("pcolor", pcolor);
            product.append("categorycode", categorycode);
            product.append("brandcode", brandcode);
            product.append("isactive", isactive);
            product.append("meas1", meas1);
            product.append("eqnum", eqnum);
            product.append("meas2", meas2);
            product.append("eqnum2", eqnum2);
            product.append("meas3", meas3);
            product.append("abbr", abbr); 
            product.append("barcode", barcode); 
            product.append("ucost", ucost); 
            product.append("markup", markup); 
            product.append("uprice", uprice); 
            product.append("acost", acost); 
            product.append("reorder", reorder);  
            product.append("purchaseitem", purchaseitem);
            product.append("accountcode", accountcode);
            product.append("vatdesc", vatdesc);
            product.append("remarks", remarks);  
            product.append("prodname", prodname);         
            $.ajax({
               url:"ajax/product_save_record.ajax.php",
               method: "POST",
               data: product,
               cache: false,
               contentType: false,
               processData: false,
               dataType:"text",
               success:function(answer){
               },
               error: function () {
                  alert("Oops. Something went wrong!");
               },
               complete: function () {
                 swal.fire({
                    title: 'Product save successfully!',
                    type: 'success',
                    confirmButtonText: 'Got it!',
                    confirmButtonClass: 'btn btn-outline-success',
                    allowOutsideClick: false,
                    buttonsStyling: false
                 }).then(function(result){
                    if(result.value) {              
                      $("#btn-new").click();
                    }
                 })
               }
            })
          }
      });
   });  

   // List of Branch Products
   $('#branchcode').on("change focus", function(){
      let branchcode = $("#branchcode").val();

      var percent = 0;
      var notice = new PNotify({
          text: "Fetching records...",
          addclass: 'stack-left-right bg-primary border-primary',
          type: 'info',
          icon: 'icon-spinner4 spinner',
          hide: false,
          buttons: {
              closer: false,
              sticker: false
          },
          opacity: .9,
          width: "190px"
      });

      var data = new FormData();
      data.append("branchcode", branchcode);
      $.ajax({
           url:"ajax/list_branch_products.ajax.php",   
           method: "POST",                
           data: data,                    
           cache: false,                  
           contentType: false,            
           processData: false,            
           dataType:"json",               
           success:function(answer){
                $(".branchProductsTable").DataTable().clear();
                for(var p = 0; p < answer.length; p++) {
                  percent = Math.round(p/answer.length*100);
                  var options = {
                    text: percent + "% complete."
                  };

                  let prod = answer[p];
                  let prodid = prod.prodid;

                  let brandname = prod.brandname;
                  let catdescription = prod.catdescription;
                  let prodname = prod.prodname;
                  let ucost = numberWithCommas(prod.ucost);
                  let markup = numberWithCommas(prod.markup);
                  let uprice = numberWithCommas(prod.uprice);
                  let accountdesc = prod.accountdesc; 

                  var p_brand = (brandname != '') ? brandname + ' ': '';                  
                  var prod_name = p_brand + prodname;                             

                  var is_active = prod.isactive;
                  if (is_active == 1){
                     var isactive = "<td><span class='badge badge-success'>Active</span></td>";
                  }else{
                     var isactive = "<td><span class='badge badge-danger'>Inactive</span></td>"
                  }

                  var buttons = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnEditBranchProduct' prodname='"+prod_name+"' uprice='"+uprice+"' pstatus='"+is_active+"' prodid='"+prodid+"'><i class='icon-check'></i></button></td>";  
                  bp.row.add([prod_name, catdescription, ucost, markup, uprice, isactive, buttons]);            
             }
             bp.draw();

             notice.update(options);
             notice.remove();
             return;
           }
      })    
   });    

   $("#btn-update-branch-product").click(function(){
     let uprice = $("#num-uprice").val();
     let isactive = $("#chk-isactive").val();
     let branchprod = $("#branchcode").val() + $("#txt-prodid").val();

     var data = new FormData();

     data.append("uprice", uprice);
     data.append("isactive", isactive);
     data.append("branchprod", branchprod);

     $.ajax({
          url:"ajax/update_branch_product.ajax.php",
          method: "POST",
          data: data,
          cache: false,
          contentType: false,
          processData: false,
          dataType:"json",
          success:function(answer){
             swal.fire({
                title: "Branch product has been successfully updated!",
                type: "success",
                showConfirmButton: true,
                confirmButtonText: "Ok",
                confirmButtonClass: "btn btn-light btn-lg",
                allowOutsideClick: false
                }).then(function(result){
                  if (result.value) {
                     $('#num-uprice').val('0.00');
                     $("#modal-branch-product").modal('hide');
                     $("#branchcode").focus();
                  }
             });
          }
      })     
   });  

   $(".branchProductsTable tbody").on('click', '.btnEditBranchProduct', function () {
     var idProduct = $(this).attr("idProduct");
     var prodname = $(this).attr("prodname");
     var uprice = $(this).attr("uprice");
     var isactive = $(this).attr("pstatus");
     var prodid = $(this).attr("prodid");

     $("#modal-branch-product").modal('show');

     $("#branch-product-name").html(prodname);
     $("#cur-uprice").val(uprice);
     $("#txt-prodid").val(prodid);

     if (isactive == '1'){
        $("#chk-isactive").prop( "checked", true);
        $("#chk-isactive").val('1');
     }else{
        $("#chk-isactive").prop( "checked", false);
        $("#chk-isactive").val('0');
     }
   }) 

   // When browsing Purchase Order - set Status to Pending | Partial
   // This will prevent AJAX from running twice
   $("#btn-search").click(function(){
      $(".productTable").DataTable().clear();
      pr.draw();
      $('#lst-categorycode').val('').trigger('change');
   });   

   // Search Product - Modal Form dynamic selector
   $('#lst-categorycode, #lst-brandcode, #lst-accountcode, #lst-meas1, #lst-vatdesc').on("change", function(){
      let categorycode = $("#lst-categorycode").val();
      let brandcode = $("#lst-brandcode").val();
      let accountcode = $("#lst-accountcode").val();
      let meas1 = $("#lst-meas1").val();
      let vatdesc = $("#lst-vatdesc").val();

      var percent = 0;
      var notice = new PNotify({
          text: "Fetching records...",
          addclass: 'stack-left-right bg-primary border-primary',
          type: 'info',
          icon: 'icon-spinner4 spinner',
          hide: false,
          buttons: {
              closer: false,
              sticker: false
          },
          opacity: .9,
          width: "190px"
      });      

      var data = new FormData();
      data.append("categorycode", categorycode);
      data.append("brandcode", brandcode);
      data.append("accountcode", accountcode);
      data.append("meas1", meas1);
      data.append("vatdesc", vatdesc);

      $.ajax({
           url:"ajax/product_list.ajax.php",   
           method: "POST",                
           data: data,                    
           cache: false,                  
           contentType: false,            
           processData: false,            
           dataType:"json",               
           success:function(answer){
                $(".productTable").DataTable().clear();
                for(var i = 0; i < answer.length; i++) {
                  percent = Math.round(i/answer.length*100);
                  var options = {
                    text: percent + "% complete."
                  };

                  let prod = answer[i];
                  let prodid = prod.prodid;

                  let brandname = prod.brandname;
                  let catdescription = prod.catdescription;
                  let prodname = prod.prodname;
                  let ucost = numberWithCommas(prod.ucost);
                  let markup = numberWithCommas(prod.markup);
                  let uprice = numberWithCommas(prod.uprice);
                  let accountdesc = prod.accountdesc;                    

                  var p_brand = (brandname != '') ? brandname + ' ': '';                  

                  var prod_name = p_brand + prodname;
                  var button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnEditProduct' prodid='"+prodid+"'><i class='icon-check'></i></button></td>";  
                  pr.row.add([prod_name, catdescription, ucost, markup, uprice, accountdesc, button]); 
                }
                pr.draw();

                notice.update(options);
                notice.remove();
                return;
           },
           beforeSend: function() {
           },  
           complete: function() {
           }, 
      })    
   });   


   $(".productTable tbody").on('click', '.btnEditProduct', function () {
	    var prodid = $(this).attr("prodid");
	    var data = new FormData();
      data.append("prodid", prodid);
      $.ajax({
     	 url:"ajax/get_product_record.ajax.php",
      	 method: "POST",
      	 data: data,
      	 cache: false,
      	 contentType: false,
      	 processData: false,
      	 dataType:"json",
      	 success:function(answer){
      	 	  // $("#num-id").val(answer["id"]);
      	 	  $("#txt-prodid").val(answer["prodid"]);
            $("#tns-prodclass").val(answer["prodclass"]);
            $("#tns-pdesc").val(answer["pdesc"]);
            $("#tns-dimension").val(answer["dimension"]);
            $("#sel-size").val(answer["size"]).trigger('change');
            $("#sel-pcolor").val(answer["pcolor"]).trigger('change');
            $("#sel-categorycode").val(answer["categorycode"]).trigger('change');
            $("#sel-brandcode").val(answer["brandcode"]).trigger('change');

        	 	if (answer["isactive"] == '1'){
        	 		$("#chk-isactive").prop( "checked", true);
        	 		$("#chk-isactive").val('1');
        	 	}else{
        	 		$("#chk-isactive").prop( "checked", false);
        	 		$("#chk-isactive").val('0');
        	 	}

            $("#sel-meas1").val(answer["meas1"]).trigger('change');
            $("#num-eqnum").val(numberWithCommas(answer["eqnum"]));
            $("#sel-meas2").val(answer["meas2"]).trigger('change');
            $("#num-eqnum2").val(numberWithCommas(answer["eqnum2"]));
            $("#sel-meas3").val(answer["meas3"]).trigger('change');
      	 	  $("#tns-abbr").val(answer["abbr"]);
      	 	  $("#tns-barcode").val(answer["barcode"]);
            $("#num-ucost").val(numberWithCommas(answer["ucost"]));
            $("#num-markup").val(numberWithCommas(answer["markup"]));
            $("#num-uprice").val(numberWithCommas(answer["uprice"]));
            $("#num-acost").val(numberWithCommas(answer["acost"]));
            $("#num-reorder").val(numberWithCommas(answer["reorder"]));

            if (answer["purchaseitem"] == '1'){
               $("#chk-purchaseitem").prop( "checked", true);
               $("#chk-purchaseitem").val('1');
            }else{
               $("#chk-purchaseitem").prop( "checked", false);
               $("#chk-purchaseitem").val('0');
            }  

            $("#sel-accountcode").val(answer["accountcode"]).trigger('change');
            $("#sel-vatdesc").val(answer["vatdesc"]).trigger('change');
            $("#tns-remarks").val(answer["remarks"]);          

      	 	  $("#trans_type").val("Update");
          	$("#modal-search-product").modal('hide');
      	}
      })
   }); 
});
