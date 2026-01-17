var pl = $('.transactionProductsTable').DataTable({
    ajax: "ajax/list_all_items.ajax.php",
    autoWidth: true,
    deferRender: true,
    processing: true,
    scrollY: 431,
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

if (!$.fn.DataTable.isDataTable('.returnTransactionTable')) {
  var rt = $('.returnTransactionTable').DataTable({
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
  $('#lst_date_range').daterangepicker({
    ranges:{
      'Today'         : [moment(),moment()],
      'Yesterday'     : [moment().subtract(1,'days'), moment().subtract(1,'days')],
      'Last 7 Days'   : [moment().subtract(6,'days'), moment()],
      'This Month'    : [moment().startOf('month'), moment().endOf('month')]
    }
  })

  // When browsing Returns - set Status to Posted | Cancelled
  // This will prevent AJAX from running twice
  $("#btn-search").click(function(){
     $(".returnTransactionTable").DataTable().clear();
     rt.draw();
     $('#lst-retstatus').val('Posted').trigger('change');
  });    

  $("#lbl-lst-empid").click(function(){
      $("#lst-empid").val('').trigger('change');
  });

  $("#lbl-lst-retstatus").click(function(){
      $("#lst-retstatus").val('').trigger('change');
  });        

  // Search Return - Modal Form dynamic selector
  $('#lst_date_range, #lst-empid, #lst-retstatus').on("change", function(){
    let date_range = $("#lst_date_range").val();
    let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
    let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);

    let empid = $("#lst-empid").val();
    let retstatus = $("#lst-retstatus").val();

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
    data.append("start_date", start_date);
    data.append("end_date", end_date);
    data.append("empid", empid);
    data.append("retstatus", retstatus);

    $.ajax({
        url:"ajax/return_transaction_list.ajax.php",   
        method: "POST",                
        data: data,                    
        cache: false,                  
        contentType: false,            
        processData: false,            
        dataType:"json",               
        success:function(answer){
              $(".returnTransactionTable").DataTable().clear();
              for(var i = 0; i < answer.length; i++) {
                percent = Math.round(i/answer.length*100);
                var options = {
                  text: percent + "% complete."
                };

                let ri = answer[i];
                let ret_date = ri.retdate;
                let retdate = ret_date.split("-");
                retdate = retdate[1] + "/" + retdate[2] + "/" + retdate[0];

                let returnby = ri.return_by;
                let retnumber = ri.retnumber;
                let retstatus = ri.retstatus;

                var button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnEditReturn' retnumber='"+retnumber+"'><i class='icon-pencil3'></i></button></td>";  
                rt.row.add([retdate, returnby, retnumber, retstatus, button]); 
              }
              rt.draw();

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

  // Get Return Record
  $(".returnTransactionTable tbody").on('click', '.btnEditReturn', function () {
    var retnumber = $(this).attr("retnumber");
    var data = new FormData();
    data.append("retnumber", retnumber);
    $.ajax({
      url:"ajax/return_get_record.ajax.php",
      method: "POST",
      data: data,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(answer){
          // $("#sel-istatus").val(answer["istatus"]).trigger('change');
          $("#sel-returnby").val(answer["returnby"]).trigger('change');

          let retdate = answer["retdate"].split("-");
          retdate = retdate[1] + "/" + retdate[2] + "/" + retdate[0];
          if (retdate == '00/00/0000'){      
            retdate = '';
          }
          $("#date-retdate").val(retdate);
          $("#txt-retstatus").val(answer["retstatus"]);
          $("#txt-retnumber").val(answer["retnumber"]);
          $("#tns-remarks").val(answer["remarks"]);

          $("#productList").val(answer["productlist"]);

          $(".enlisted_products").empty();

          // Reload products table - restore all button to Green
          // After the data is retrieved - selected products turned Red (Deactivated)
          $(".transactionProductsTable").DataTable().ajax.reload();
          
          var data_items = new FormData();
          data_items.append("retnumber", retnumber);
          $.ajax({
            url:"ajax/return_get_items.ajax.php",
            method: "POST",
            data: data_items,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(products){
              for(var p = 0; p < products.length; p++) {
                var product = products[p];
                var itemid = product.itemid;
                var istatus = product.istatus;

                var product_name = product.pdesc;
                var meas_2 = product.meas2;

                var meas2 = meas_2.toUpperCase();
                var pdesc = product_name + ' (' + meas2 + ')';

                var qty = numberWithCommas(product.qty);

                if (istatus == "Good"){
                  $(".enlisted_products").append(
                    '<tr>'+   
                    '<td width="50%" style="padding:2px;">'+   
                      '<div class="input-group">'+
                          '<span style="padding:2px;" class="input-group-prepend"><button type="button" style="color:coral;" class="btn btn-sm btn-light removeProduct" itemid="'+itemid+'"><i class="icon-undo"></i></button></span>'+         
                          '<input type="text" style="padding:2px;" class="form-control pdesc" itemid="'+itemid+'" name="addProduct" value="'+pdesc+'" readonly required>'+
                        '</div>'+
                    '</td>'+

                    '<td class="itemStatus" width="15%" style="padding:2px;">'+
                      '<select data-placeholder="< Status >" class="form-control select-search istatus" itemid="'+itemid+'" id="sel-istatus" name="sel-istatus">'+
                        '<option selected value="Good">Good</option>'+
                        '<option value="Bad">Bad</option>'+
                        '<option value="Safekeep">Safekeep</option>'+
                      '<select>'+
                    '</td>' +

                    '<td class="qtyEntry" width="15%" style="padding:2px;">'+
                      '<input type="text" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control qty numeric" itemid="'+itemid+'" name="qty" value="'+qty+'" required>'+
                    '</td>' +                                 
                  '</tr>');
                }else if (istatus == "Bad"){
                  $(".enlisted_products").append(
                      '<tr>'+   
                      '<td width="50%" style="padding:2px;">'+   
                        '<div class="input-group">'+
                            '<span style="padding:2px;" class="input-group-prepend"><button type="button" style="color:coral;" class="btn btn-sm btn-light removeProduct" itemid="'+itemid+'"><i class="icon-undo"></i></button></span>'+         
                            '<input type="text" style="padding:2px;" class="form-control pdesc" itemid="'+itemid+'" name="addProduct" value="'+pdesc+'" readonly required>'+
                          '</div>'+
                      '</td>'+
  
                      '<td class="itemStatus" width="15%" style="padding:2px;">'+
                        '<select data-placeholder="< Status >" class="form-control select-search istatus" itemid="'+itemid+'" id="sel-istatus" name="sel-istatus">'+
                          '<option value="Good">Good</option>'+
                          '<option selected value="Bad">Bad</option>'+
                          '<option value="Safekeep">Safekeep</option>'+
                        '<select>'+
                      '</td>' +
  
                      '<td class="qtyEntry" width="15%" style="padding:2px;">'+
                        '<input type="text" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control qty numeric" itemid="'+itemid+'" name="qty" value="'+qty+'" required>'+
                      '</td>' +                                 
                  '</tr>');
                }else{
                  $(".enlisted_products").append(
                    '<tr>'+   
                    '<td width="50%" style="padding:2px;">'+   
                      '<div class="input-group">'+
                          '<span style="padding:2px;" class="input-group-prepend"><button type="button" style="color:coral;" class="btn btn-sm btn-light removeProduct" itemid="'+itemid+'"><i class="icon-undo"></i></button></span>'+         
                          '<input type="text" style="padding:2px;" class="form-control pdesc" itemid="'+itemid+'" name="addProduct" value="'+pdesc+'" readonly required>'+
                        '</div>'+
                    '</td>'+

                    '<td class="itemStatus" width="15%" style="padding:2px;">'+
                      '<select data-placeholder="< Status >" class="form-control select-search istatus" itemid="'+itemid+'" id="sel-istatus" name="sel-istatus">'+
                        '<option value="Good">Good</option>'+
                        '<option value="Bad">Bad</option>'+
                        '<option selected value="Safekeep">Safekeep</option>'+
                      '<select>'+
                    '</td>' +

                    '<td class="qtyEntry" width="15%" style="padding:2px;">'+
                      '<input type="text" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control qty numeric" itemid="'+itemid+'" name="qty" value="'+qty+'" required>'+
                    '</td>' +                                 
                  '</tr>');
                }
              }
              // listProducts();                   
              removeAddedProducts();
            }
          });    
          $("#btn-print").prop('disabled', false);
          $("#btn-save").prop('disabled', false);
          $("#trans_type").val("Update");
          $("#modal-search-return").modal('hide');
      }
    });
  });  

  $(".transactionProductsTable tbody").on("click", "button.addProduct", function(){
     var itemid = $(this).attr("itemid");
  
     $(this).removeClass("btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct");
     $(this).addClass("btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2");

     var data = new FormData();
     data.append("itemid", itemid);
     $.ajax({
      url:"ajax/get_product_details.ajax.php", 
        method: "POST",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        dataType:"json",
        success:function(answer){
          let meas = answer["meas2"];
          var pdesc = answer["pdesc"] + ' (' + meas.toUpperCase() + ')';
          var price_amount = answer["ucost"];
          var price = numberWithCommas(price_amount);

           $(".enlisted_products").append(
            '<tr>'+   
            '<td width="70%" style="padding:2px;">'+   
               '<div class="input-group">'+
                  '<span style="padding:2px;" class="input-group-prepend"><button type="button" style="color:coral;" class="btn btn-sm btn-light removeProduct" itemid="'+itemid+'"><i class="icon-undo"></i></button></span>'+         
                  '<input type="text" style="padding:2px;" class="form-control pdesc" itemid="'+itemid+'" name="addProduct" value="'+pdesc+'" readonly required>'+
                '</div>'+
            '</td>'+  
            
            '<td class="itemStatus" width="15%" style="padding:2px;">'+
               '<select data-placeholder="< Status >" class="form-control select-search istatus" name="istatus">'+
                  '<option value="Good">Good</option>'+
                  '<option value="Bad">Bad</option>'+
                  '<option value="Safekeep">Safekeep</option>'+
               '<select>'+
            '</td>' +            

            '<td class="qtyEntry" width="15%" style="padding:2px;">'+
               '<input type="text" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control qty numeric" itemid="'+itemid+'" name="qty" value="0.00" required>'+
            '</td>' +                               
          '</tr>')

          listItems();
          $('.qty').focus();
        }
     })
  });

  $(".return-form").on("keydown keypress blur focus", "input.qty", function(){
      var itemid = $(this).parent().parent().children(".qtyEntry").children().attr("itemid");
      _gblBindNumericClasses('numeric'); 
      listItems(); 
  });

  $(".return-form").on("change", ".istatus", function(){
    listItems(); 
  });  

  var idRemoveProduct = [];
  localStorage.removeItem("removeProduct");
  $(".return-form").on("click", "button.removeProduct", function(){
     $(this).parent().parent().parent().parent().remove();
     var itemid = $(this).attr("itemid");

     if(localStorage.getItem("removeProduct") == null){
       idRemoveProduct = [];
     }else{
       idRemoveProduct.concat(localStorage.getItem("removeProduct"))
     }

     idRemoveProduct.push({"itemid":itemid});
     localStorage.setItem("removeProduct", JSON.stringify(idRemoveProduct));

     $("button.recoverButton[itemid='"+itemid+"']").removeClass('btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2');
     $("button.recoverButton[itemid='"+itemid+"']").addClass('btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct');

     listItems();
    
     var a = document.getElementById("product_list");
     var rows = a.rows.length;
  })

  $(".transactionProductsTable").on("draw.dt", function(){
     if(localStorage.getItem("removeProduct") != null){
      var listIdProducts = JSON.parse(localStorage.getItem("removeProduct"));
      for(var i = 0; i < listIdProducts.length; i++){
        $("button.recoverButton[itemid='"+listIdProducts[i]["itemid"]+"']").removeClass('btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2');
        $("button.recoverButton[itemid='"+listIdProducts[i]["itemid"]+"']").addClass('tn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct');
      }
     }
  })  

  // Remove Added Products
  function removeAddedProducts(){
     var itemid = $(".removeProduct");     
     var tableButtons = $(".transactionProductsTable tbody button.addProduct");
     for(var i = 0; i < itemid.length; i++){
       var button = $(itemid[i]).attr("itemid");
       for(var j = 0; j < tableButtons.length; j ++){
         if($(tableButtons[j]).attr("itemid") == button){
           $(tableButtons[j]).removeClass("tn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct");
           $(tableButtons[j]).addClass("btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2");
         }
      }
     }
  }

  $('.transactionProductsTable').on('draw.dt', function(){
    removeAddedProducts();
  })      

  function listItems(){
      var productList = [];
      var description = $(".pdesc");
      var item_status = $(".istatus");
      var quantity = $(".qty");

      var hasZeroQty = false;

      var num_entries = description.length; 
      if (num_entries > 0){
       for(var i = 0; i < num_entries; i++){
        var txt_qty = $(quantity[i]).val();

        // Check if Qty or Price = 0.00
        if ((txt_qty == "0.00")||!(txt_qty)){  
          var hasZeroQty = true;
        }

        var istatus = $(item_status[i]).val();
        var qty = parseFloat(txt_qty.replaceAll(",",""));

        productList.push({"qty" : qty.toFixed(2),
                          "istatus" : istatus,
                          "itemid" : $(description[i]).attr("itemid")})      
       }

       $("#productList").val(JSON.stringify(productList));

       if (hasZeroQty){
         $("#btn-save").prop('disabled', true);
       }else{
         $("#btn-save").prop('disabled', false);
       }
      }else{
       $("#btn-save").prop('disabled', true);
     }
  } 

  $(".return-form").submit(function (e) {
      e.preventDefault();
      swal.fire({
          title: 'Do you want to save return transaction?',
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
            let trans_type = $("#trans_type").val();
            let returnby = $("#sel-returnby").val();
            let format_retdate = $("#date-retdate").val().split("/");
            format_retdate = format_retdate[2] + "-" + format_retdate[0] + "-" + format_retdate[1];
            
            let retdate = format_retdate;
            let retnumber = $("#txt-retnumber").val();
            let retstatus = $("#txt-retstatus").val();
            let remarks = $("#tns-remarks").val();
            let postedby = $("#tns-postedby").val();
            let productlist = $("#productList").val();          

            var returns = new FormData();
            returns.append("trans_type", trans_type);
            returns.append("returnby", returnby);
            returns.append("retdate", retdate);
            returns.append("retnumber", retnumber);
            returns.append("retstatus", retstatus);
            returns.append("remarks", remarks);
            returns.append("postedby", postedby);
            returns.append("productlist", productlist);
           
            $.ajax({
               url:"ajax/return_save_record.ajax.php",
               method: "POST",
               data: returns,
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
                    title: 'Return transaction successfully saved!',
                    type: 'success',
                    confirmButtonText: 'Proceed',
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
});    