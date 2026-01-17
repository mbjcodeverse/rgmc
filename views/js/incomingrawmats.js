$('.transactionProductsTable').DataTable({
    ajax: "ajax/list_all_purchaseitems.ajax.php",
    autoWidth: true,
    deferRender: true,
    processing: true,
    scrollY: 431,
    pagelength: 25,
    lengthMenu: [[25, 50, -1], [25, 50, "All"]],
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"p>',    //ip - Showing # of entries + Pagination - datatable footer
            language: {
                search: '<span>Filter:</span> _INPUT_',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
            },
            columnDefs: [
                {
                    "targets": 1, // Target the 2nd column (0-indexed)
                    "visible": false // Hide the column
                }
            ]
});

if (!$.fn.DataTable.isDataTable('.incomingRawMatsTransactionTable')) {
  var pt = $('.incomingRawMatsTransactionTable').DataTable({
      deferRender: true,
      processing: true,
      autoWidth: true,
      scrollY: 360,
      pagelength: 25,
      lengthMenu: [[25, 50], [25, 50]],
      dom: '<"datatable-header"><"datatable-scroll"t><"datatable-footer"fp>',
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
      'This Month'    : [moment().startOf('month'), moment().endOf('month')],
      'All'           : [moment('2025-06-01'), moment()],
    }
  });
  // $(".transactionProductsTable *").attr('disabled', 'disabled');

  // Add Products
  $(".transactionProductsTable tbody").on("click", "button.addProduct", function(){
    var itemid = $(this).attr("itemid");
    //alert(itemid);
    
    $(this).removeClass("btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct");
    $(this).addClass("btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2");

    var data = new FormData();
     data.append("itemid", itemid);
     $.ajax({
      url:"ajax/get_rawmats_details.ajax.php", 
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
            '<td width="50%" style="padding:2px;">'+   
               '<div class="input-group">'+
                  '<span style="padding:2px;" class="input-group-prepend"><button type="button" style="color:coral;" class="btn btn-sm btn-light removeProduct" itemid="'+itemid+'"><i class="icon-undo"></i></button></span>'+         
                  '<input type="text" style="padding:2px;" class="form-control pdesc" itemid="'+itemid+'" name="addProduct" value="'+pdesc+'" readonly required>'+
                '</div>'+
            '</td>'+            

            '<td class="qtyEntry" width="15%" style="padding:2px;">'+
               '<input type="text" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control qty numeric" itemid="'+itemid+'" name="qty" value="0.00" required>'+
            '</td>' +  

            '<td class="priceEntry" width="15%" style="padding:2px;">'+
               '<input type="text" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control price numeric" itemid="'+itemid+'" name="price" value="'+price+'" required>'+
            '</td>' +   

            '<td class="totalAmount" width="15%" style="padding:2px;">'+
               '<input type="text" style="padding:2px;padding-right:17px;text-align:right;" class="form-control tamount" productPrice="'+price+'" name="tamount" value="0.00" readonly required>'+
            '</td>' +                                
          '</tr>')

          addingTotalPrices();
          listProducts();
          $('.qty').focus();
        }
     })
  });

  // Input QTY or PRICE
  $(".incoming-rawmats-form").on("keydown keypress blur focus", "input.qty,input.price", function(){
      var itemid = $(this).parent().parent().children(".qtyEntry").children().attr("itemid");

      var q = $(this).parent().parent().children(".qtyEntry").children().val();
      var quantity = q.replaceAll(",","");

      var p = $(this).parent().parent().children(".priceEntry").children().val();
      var price = p.replaceAll(",","");   

      var totalAmount = quantity * price;
      
      var productAmount = $(this).parent().parent().children(".totalAmount").children(".tamount");
      productAmount.val(numberWithCommas(totalAmount.toFixed(2)));

      _gblBindNumericClasses('numeric'); 

      addingTotalPrices();
      listProducts(); 
  }) 

  // Discount Entry
  $('#num-discount').on("change keyup", function(){
    let num_amount = $('#num-amount').val();
    let num_discount = $('#num-discount').val();
    let amount = parseFloat(num_amount.replaceAll(",",""));
    let discount= parseFloat(num_discount.replaceAll(",",""));
    let result = amount - discount;
    $('#num-netamount').val(numberWithCommas(result.toFixed(2)));
  });  

  // Remove Product from Table
  var idRemoveProduct = [];
  localStorage.removeItem("removeProduct");
  $(".incoming-rawmats-form").on("click", "button.removeProduct", function(){
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

    addingTotalPrices();
    listProducts();
    
    var a = document.getElementById("product_list");
    var rows = a.rows.length;
  })  

  // Add Total Amount from Table
  function addingTotalPrices(){
    var priceItem = $(".tamount");
    if (priceItem.length > 0){
      var arrayAdditionPrice = [];  
      for(var i = 0; i < priceItem.length; i++){
         var num = $(priceItem[i]).val();
         var total_amount = parseFloat(num.replaceAll(",",""));
         arrayAdditionPrice.push(total_amount);
      }

      function additionArrayPrices(total, numberArray){
        return total + numberArray;
      }
      var addingTotalPrice = arrayAdditionPrice.reduce(additionArrayPrices);

      $("#num-amount").val(numberWithCommas(addingTotalPrice.toFixed(2)));
      var netamount = addingTotalPrice.toFixed(2) - $('#num-discount').val();
      $("#num-netamount").val(numberWithCommas(netamount.toFixed(2)));
    }else{
      $("#num-amount,#num-discount,#num-netamount").val('0.00');
    }   
  }  

  // List Products  
  function listProducts(){
    var productList = [];
    var description = $(".pdesc");
    var quantity = $(".qty");
    var priceamount = $(".price");
    var totalamount = $(".tamount");

    var hasZeroQty = false;

    var num_entries = description.length; 
    if (num_entries > 0){
      for(var i = 0; i < num_entries; i++){
        var txt_qty = $(quantity[i]).val();
        var txt_price = $(priceamount[i]).val();
        var txt_tamount = $(totalamount[i]).val();

        // Check if Qty or Price = 0.00
        if ((txt_qty == "0.00")||!(txt_qty)||(txt_price == "0.00")){  
          var hasZeroQty = true;
        }

        //Remove commas on values
        var qty = parseFloat(txt_qty.replaceAll(",",""));
        var price = parseFloat(txt_price.replaceAll(",",""));
        var tamount = parseFloat(txt_tamount.replaceAll(",",""));

        productList.push({"qty" : qty.toFixed(2),
                          "price" : price.toFixed(2),
                          "tamount" : tamount.toFixed(2),
                          "itemid" : $(description[i]).attr("itemid")})      
      }
      // alert("Mom = " + JSON.stringify(productList));
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

   // SAVE Purchase Order
   $(".incoming-rawmats-form").submit(function (e) {
      e.preventDefault();
      swal.fire({
          title: 'Do you want to incoming raw materials transaction?',
          // text: "You won't be able to revert this!",
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

            let format_podate = $("#date-podate").val().split("/");
            format_podate = format_podate[2] + "-" + format_podate[0] + "-" + format_podate[1];

            var suppliercode = $("#sel-suppliercode").val();
            var podate = format_podate;
            var postatus = $("#txt-postatus").val();
            var ponumber = $("#txt-ponumber").val();
            var orderedby = $("#sel-orderedby").val();
            var machineid = $("#sel-machineid").val();
            var preparedby = $("#tns-preparedby").val();
            var remarks = $("#tns-remarks").val();
            var txt_amount = $("#num-amount").val();
            var txt_discount = $("#num-discount").val();
            var txt_netamount = $("#num-netamount").val();
            var productlist = $("#productList").val();

            // Remove comma on values
            var amount = parseFloat(txt_amount.replaceAll(",",""));
            var discount= parseFloat(txt_discount.replaceAll(",",""));
            var netamount= parseFloat(txt_netamount.replaceAll(",",""));            

            var purchaseorder = new FormData();
            purchaseorder.append("trans_type", trans_type);

            purchaseorder.append("suppliercode", suppliercode);
            purchaseorder.append("podate", podate);
            purchaseorder.append("postatus", postatus);
            purchaseorder.append("ponumber", ponumber);
            purchaseorder.append("orderedby", orderedby);
            purchaseorder.append("machineid", machineid);
            purchaseorder.append("preparedby", preparedby);
            purchaseorder.append("remarks", remarks);
            purchaseorder.append("amount", amount);
            purchaseorder.append("discount", discount);
            purchaseorder.append("netamount", netamount);
            purchaseorder.append("productlist", productlist);            
            $.ajax({
               url:"ajax/incomingrawmats_save_record.ajax.php",
               method: "POST",
               data: purchaseorder,
               cache: false,
               contentType: false,
               processData: false,
               dataType:"text",
               success:function(answer){
                //   if (trans_type == 'New'){
                //      let ponumber = answer;
                //      window.open("extensions/tcpdf/pdf/purchaseorder.php?ponumber="+ponumber, "_blank");
                //   }else{
                //      $("#btn-print").click();
                //   }    
               },
               error: function () {
                  alert("Oops. Something went wrong!");
               },
               complete: function () {
                 // $("#btn-new").click();
                 swal.fire({
                    title: 'Incoming transaction successfully saved!',
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
          else if(result.dismiss === swal.DismissReason.cancel) {
              // swal.fire(
              //     'Cancelled',
              //     'Your imaginary file is safe :)',
              //     'error'
              // );
          }
      });
   });  

   // When browsing Purchase Order - set Status to Pending | Partial
   // This will prevent AJAX from running twice
   $("#btn-search").click(function(){
      $(".incomingRawMatsTransactionTable").DataTable().clear();
      pt.draw();
      $('#lst-postatus').val('Pending | Partial').trigger('change');
   });   

   $("#lbl-lst-sel-machineid").click(function(){
    $("#sel-machineid").val('').trigger('change');
   });

   $("#lbl-lst-machineid").click(function(){
      $("#lst-machineid").val('').trigger('change');
   }); 

   $("#lbl-lst-suppliercode").click(function(){
      $("#lst-suppliercode").val('').trigger('change');
   });

   $("#lbl-lst-postatus").click(function(){
      $("#lst-postatus").val('').trigger('change');
   });      

   // Search PO - Modal Form dynamic selector
   $('#lst-machineid, #lst_date_range, #lst-suppliercode, #lst-postatus').on("change", function(){
      let machineid = $("#lst-machineid").val();

      let date_range = $("#lst_date_range").val();
      let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
      let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);

      let suppliercode = $("#lst-suppliercode").val();
      let postatus = $("#lst-postatus").val();

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
      data.append("machineid", machineid);
      data.append("start_date", start_date);
      data.append("end_date", end_date);
      data.append("suppliercode", suppliercode);
      data.append("postatus", postatus);

      $.ajax({
           url:"ajax/incomingrawmats_transaction_list.ajax.php",   
           method: "POST",                
           data: data,                    
           cache: false,                  
           contentType: false,            
           processData: false,            
           dataType:"json",               
           success:function(answer){
                $(".incomingRawMatsTransactionTable").DataTable().clear();
                for(var i = 0; i < answer.length; i++) {
                  percent = Math.round(i/answer.length*100);
                  var options = {
                    text: percent + "% complete."
                  };

                  let po = answer[i];
                  let po_date = po.podate;
                  let podate = po_date.split("-");
                  podate = podate[1] + "/" + podate[2] + "/" + podate[0];

                  let name = po.name;
                  let ponumber = po.ponumber;
                  let machinedesc = po.machinedesc;
                  let postatus = po.postatus;
                  let netamount = numberWithCommas(po.netamount);

                  var button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnEditPurchaseOrder' ponumber='"+ponumber+"'><i class='icon-pencil3'></i></button></td>";  
                  pt.row.add([podate, name, ponumber, machinedesc, postatus, netamount, button]); 
                }
                pt.draw();

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

   // Get Purchase Order Record
   $(".incomingRawMatsTransactionTable tbody").on('click', '.btnEditPurchaseOrder', function () {
      var ponumber = $(this).attr("ponumber");
      var data = new FormData();
      data.append("ponumber", ponumber);
      $.ajax({
         url:"ajax/incomingrawmats_get_record.ajax.php",
         method: "POST",
         data: data,
         cache: false,
         contentType: false,
         processData: false,
         dataType:"json",
         success:function(answer){
            $("#sel-suppliercode").val(answer["suppliercode"]).trigger('change');

            let podate = answer["podate"].split("-");
            podate = podate[1] + "/" + podate[2] + "/" + podate[0];
            if (podate == '00/00/0000'){      
              podate = '';
            }
            $("#date-podate").val(podate);
            $("#txt-postatus").val(answer["postatus"]);
            $("#txt-ponumber").val(answer["ponumber"]);
            $("#sel-orderedby").val(answer["orderedby"]).trigger('change');
            $("#sel-machineid").val(answer["machineid"]).trigger('change');
            $("#tns-remarks").val(answer["remarks"]);
            $("#num-amount").val(numberWithCommas(answer["amount"]));
            $("#num-discount").val(numberWithCommas(answer["discount"]));
            $("#num-netamount").val(numberWithCommas(answer["netamount"]));

            $("#productList").val(answer["productlist"]);

            $(".enlisted_products").empty();

            // Reload products table - restore all button to Green
            // After the data is retrieved - selected products turned Red (Deactivated)
            $(".transactionProductsTable").DataTable().ajax.reload();
            
            var data_items = new FormData();
            data_items.append("ponumber", ponumber);
            $.ajax({
               url:"ajax/incomingrawmats_get_items.ajax.php",
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

                   var product_name = product.pdesc;
                   var meas_2 = product.meas2;

                   var meas2 = meas_2.toUpperCase();
                   var pdesc = product_name + ' (' + meas2 + ')';

                   var qty = numberWithCommas(product.qty);
                   var price = numberWithCommas(product.price);
                   var tamount = numberWithCommas(product.tamount);

                   $(".enlisted_products").append(
                      '<tr>'+   
                      '<td width="50%" style="padding:2px;">'+   
                         '<div class="input-group">'+
                            '<span style="padding:2px;" class="input-group-prepend"><button type="button" style="color:coral;" class="btn btn-sm btn-light removeProduct" itemid="'+itemid+'"><i class="icon-undo"></i></button></span>'+         
                            '<input type="text" style="padding:2px;" class="form-control pdesc" itemid="'+itemid+'" name="addProduct" value="'+pdesc+'" readonly required>'+
                          '</div>'+
                      '</td>'+            

                      '<td class="qtyEntry" width="15%" style="padding:2px;">'+
                         '<input type="text" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control qty numeric" itemid="'+itemid+'" name="qty" value="'+qty+'" required>'+
                      '</td>' +  

                      '<td class="priceEntry" width="15%" style="padding:2px;">'+
                         '<input type="text" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control price numeric" itemid="'+itemid+'" name="price" value="'+price+'" required>'+
                      '</td>' +   

                      '<td class="totalAmount" width="15%" style="padding:2px;">'+
                         '<input type="text" style="padding:2px;padding-right:17px;text-align:right;" class="form-control tamount" productPrice="'+price+'" name="tamount" value="'+tamount+'" readonly required>'+
                      '</td>' +                                
                   '</tr>');
                      
                 }
                 // listProducts();                   
                 removeAddedProducts();
               }
            });    

            let postatus = $("#txt-postatus").val();
            switch (postatus){
              case 'Pending':
                $("#btn-save").prop('disabled', false);

                if (($("#user_type").val() == 'Administrator')||($("#user_type").val() == 'Super Administrator')){
                  $("#btn-cancel").prop('disabled', false);
                }else{
                  $("#btn-cancel").prop('disabled', true);
                }  

                $("#btn-close").prop('disabled', true);
                break;
              case 'Cancelled':
                $("#btn-save").prop('disabled', true);
                $("#btn-cancel").prop('disabled', true);
                $("#btn-close").prop('disabled', true);
                break;
              case 'Closed':
                $("#btn-save").prop('disabled', true);
                $("#btn-cancel").prop('disabled', true);
                $("#btn-close").prop('disabled', true);
                break;                      
              case 'Partial':
                $("#btn-save").prop('disabled', false);
                $("#btn-cancel").prop('disabled', true);

                if (($("#user_type").val() == 'Administrator')||($("#user_type").val() == 'Super Administrator')){
                  $("#btn-close").prop('disabled', false);
                }else{
                  $("#btn-close").prop('disabled', true);
                }
                break;  
              case 'Completed':
                $("#btn-save").prop('disabled', false);
                $("#btn-cancel").prop('disabled', false);
                $("#btn-close").prop('disabled', true);
                break;                                          
            }

            $("#btn-print").prop('disabled', false);
            $("#btn-view").prop('disabled', false);
            $("#trans_type").val("Update"); 
            $("#modal-search-incoming-rawmats").modal('hide');
         }
      });
   }); 
   
   $('#modal-search-incoming-rawmats').on('shown.bs.modal', function () {
        $('#lst-postatus').val('Completed').trigger('change');

        // Clear DataTable filter (search box)
        if ($.fn.DataTable.isDataTable('.incomingRawMatsTransactionTable')) {
            $('.incomingRawMatsTransactionTable').DataTable().search('').draw();
        }
   });

   // View Purchase | Incoming Qty Comparison
   $("#btn-view").click(function(){
      var ponumber = $('#txt-ponumber').val();
      var data = new FormData();                     
      data.append("ponumber", ponumber);
      $.ajax({
        url:"ajax/purchaseorder_incoming_qty.ajax.php",   
        method: "POST",                
        data: data,                    
        cache: false,                  
        contentType: false,            
        processData: false,            
        dataType:"json",               
        success:function(answer){
          $("#purchase-incoming-qty").empty();

          var prev_id = 0;
          var pur_qty = 0.00;
          var inc_qty = 0.00;
          var txt_pur_qty = '';
          var txt_inc_qty = '';
          var pdesc = '';
          var last_inc_qty = '';

          for(var i = 0; i < answer.length; i++) {
            var pur_inc = answer[i];
            var itemid = pur_inc.itemid;
            var prodname = pur_inc.pdesc;
            var tdetails = pur_inc.tdetails;
            var qty = pur_inc.ttl_qty;
            var ttl_qty = Number(qty);
            // alert(tdetails + ' : ' + ttl_qty);

            if (i == 0){
              prev_id = itemid;
            }

            if ((itemid == prev_id)&&(tdetails == "Purchase")){
              txt_pur_qty = numberWithCommas(ttl_qty); 
              pdesc = prodname;
            }

            if ((itemid == prev_id)&&(tdetails == "Incoming")){
              txt_inc_qty = numberWithCommas(ttl_qty); 
              pdesc = prodname;
            }

            if (itemid != prev_id){
              $("#purchase-incoming-qty").append(
                '<tr>'+   
                  '<td>'+pdesc+'</td>'+ 
                  '<td style="text-align:right;">'+txt_pur_qty+'</td>'+
                  '<td style="text-align:right;">'+txt_inc_qty+'</td>'+                               
                '</tr>');

              prev_id = itemid;  

              if (tdetails == "Purchase"){
                 txt_pur_qty = numberWithCommas(ttl_qty);
                 pdesc = prodname;  
              } 

              if (tdetails == "Incoming"){
                 txt_inc_qty = numberWithCommas(ttl_qty); 
                 last_inc_qty = numberWithCommas(ttl_qty); 
                 pdesc = prodname;
              }
              // txt_inc_qty = '';                                                 
            }
          }

          $("#purchase-incoming-qty").append(
            '<tr>'+   
              '<td>'+pdesc+'</td>'+ 
              '<td style="text-align:right;">'+txt_pur_qty+'</td>'+
              '<td style="text-align:right;">'+last_inc_qty+'</td>'+                               
            '</tr>');
        }
      })
   });

   // Cancel PO
   $("#btn-cancel").click(function(){
      swal.fire({
          title: 'Do you want to Cancel incoming raw materials transaction?',
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
          if(result.value) {
            var ponumber = $("#txt-ponumber").val();            
            var cancelpurchase = new FormData();
            cancelpurchase.append("ponumber", ponumber);            
            $.ajax({
               url:"ajax/incomingrawmats_cancel_record.ajax.php",
               method: "POST",
               data: cancelpurchase,
               cache: false,
               contentType: false,
               processData: false,
               dataType:"text",
               success:function(answer){
               },
               error: function () {
                 swal.fire({
                    title: 'Cancellation Terminated!',
                    text: 'Something went wrong :(',
                    type: 'error',
                    confirmButtonText: 'Proceed',
                    confirmButtonClass: 'btn btn-outline-success',
                    allowOutsideClick: false,
                    buttonsStyling: false
                 }).then(function(result){
                    if(result.value) {              
                      $("#btn-new").click();
                    }
                 })
               },
               complete: function () {
                 swal.fire({
                    title: 'Cancellation Successful!',
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

   // Close PO
   $("#btn-close").click(function(){
      swal.fire({
          title: 'Do you want to Close purchase order transaction?',
          text: "You won't be able to revert this!",
          type: 'question',
          showCancelButton: true,
          confirmButtonText: 'Yes, Close!',
          cancelButtonText: 'No',
          confirmButtonClass: 'btn btn-outline-success',
          cancelButtonClass: 'btn btn-outline-danger',
          allowOutsideClick: false,
          buttonsStyling: false
      }).then(function(result) {
          if(result.value) {
            var ponumber = $("#txt-ponumber").val();            
            var closepurchase = new FormData();
            closepurchase.append("ponumber", ponumber);            
            $.ajax({
               url:"ajax/purchaseorder_close_record.ajax.php",
               method: "POST",
               data: closepurchase,
               cache: false,
               contentType: false,
               processData: false,
               dataType:"text",
               success:function(answer){
               },
               error: function () {
                 swal.fire({
                    title: 'Close Terminated!',
                    text: 'Something went wrong :(',
                    type: 'error',
                    confirmButtonText: 'Proceed',
                    confirmButtonClass: 'btn btn-outline-success',
                    allowOutsideClick: false,
                    buttonsStyling: false
                 }).then(function(result){
                    if(result.value) {              
                      $("#btn-new").click();
                    }
                 })
               },
               complete: function () {
                 swal.fire({
                    title: 'Close Successful!',
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

   $("#btn-print").click(function(){
      var ponumber = $("#txt-ponumber").val();
  	  window.open("extensions/tcpdf/pdf/purchaseorder.php?ponumber="+ponumber, "_blank");
   });   
});



