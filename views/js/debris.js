// Avoid raw materials with " character
// columnDefs - hides the second column (itemid) - for searching purposes

$('.transactionProductsTable').DataTable({
    ajax: "ajax/list_all_wastedamage.ajax.php",
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

if (!$.fn.DataTable.isDataTable('.debrisTransactionTable')) {
  var pt = $('.debrisTransactionTable').DataTable({
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
  $('#date-debdate').daterangepicker({
      minDate: moment('2025-06-30'),
      singleDatePicker: true,  
      showDropdowns: false,   
      locale: {
          format: 'MM/DD/YYYY' 
      }
  }); 

  $('#lst_date_range').daterangepicker({
    ranges:{
      'Today'         : [moment(),moment()],
      'Yesterday'     : [moment().subtract(1,'days'), moment().subtract(1,'days')],
      'Last 7 Days'   : [moment().subtract(6,'days'), moment()],
      'This Month'    : [moment().startOf('month'), moment().endOf('month')],
      'All'           : [moment('2025-06-30'), moment()],
    },
    startDate: moment().startOf('month'), 
    endDate: moment().endOf('month'),
    minDate: moment('2025-06-30')
  })

 // When browsing Purchase Order - set Status to Pending | Partial
   // This will prevent AJAX from running twice
  $("#btn-search").click(function(){
    $(".debrisTransactionTable").DataTable().clear();
    pt.draw();
    $('#lst-debstatus').val('Posted').trigger('change');
  });   

  $("#lbl-lst-machineid").click(function(){
      $("#lst-machineid").val('').trigger('change');
  }); 

  $("#lbl-lst-empid").click(function(){
      $("#lst-empid").val('').trigger('change');
  });

  $("#lbl-lst-debstatus").click(function(){
      $("#lst-debstatus").val('').trigger('change');
  });      

  $("#btn-undo").click(function(){
      $('#sel-machineid').val('').trigger('change');
  });   

  // Search Stock Withdrawal - Modal Form dynamic selector
  $('#lst-machineid, #lst_date_range, #lst-empid, #lst-debstatus').on("change", function(){
    let machineid = $("#lst-machineid").val();

    let date_range = $("#lst_date_range").val();
    let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
    let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);

    let empid = $("#lst-empid").val();
    let debstatus = $("#lst-debstatus").val();

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
    data.append("empid", empid);
    data.append("debstatus", debstatus);

    $.ajax({
        url:"ajax/debris_transaction_list.ajax.php",   
        method: "POST",                
        data: data,                    
        cache: false,                  
        contentType: false,            
        processData: false,            
        dataType:"json",               
        success:function(answer){
              $(".debrisTransactionTable").DataTable().clear();
              for(var i = 0; i < answer.length; i++) {
                percent = Math.round(i/answer.length*100);
                var options = {
                  text: percent + "% complete."
                };

                let sw = answer[i];
                let deb_date = sw.debdate;
                let debdate = deb_date.split("-");
                debdate = debdate[1] + "/" + debdate[2] + "/" + debdate[0];

                let auditor = sw.request_by;
                let debnumber = sw.debnumber;
                let shift = sw.shift;
                let machinedesc = sw.machinedesc;
                let machabbr = sw.machabbr;
                let debstatus = sw.debstatus;
                let total_amount = numberWithCommas(sw.total_amount);

                if (machinedesc != ''){
                  var machine = machinedesc + ' (' + machabbr + ')';
                }else{
                  var machine = '';
                }

                var button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnEditDebris' debnumber='"+debnumber+"'><i class='icon-pencil3'></i></button></td>";  
                pt.row.add([debdate, auditor, debnumber, shift, machine, total_amount, button]); 
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

  // Get Stock Withdrawal Record
  $(".debrisTransactionTable tbody").on('click', '.btnEditDebris', function () {
    var debnumber = $(this).attr("debnumber");
    var data = new FormData();
    data.append("debnumber", debnumber);
    $.ajax({
      url:"ajax/debris_get_record.ajax.php",
      method: "POST",
      data: data,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(answer){
          $("#sel-debrisby").val(answer["debrisby"]).trigger('change');

          let debdate = answer["debdate"].split("-");
          debdate = debdate[1] + "/" + debdate[2] + "/" + debdate[0];
          if (debdate == '00/00/0000'){      
            debdate = '';
          }
          $("#date-debdate").val(debdate);
          $("#txt-debstatus").val(answer["debstatus"]);
          $("#txt-debnumber").val(answer["debnumber"]);
          $("#txt-prodnumber").val(answer["prodnumber"]);
          $("#sel-shift").val(answer["shift"]).trigger('change');

          let machineid = answer["machineid"];
          if (machineid != ''){
            $("#sel-machineid").val(answer["machineid"]).trigger('change');
          }else{
            $("#sel-machineid").val("").trigger('change');
          }

          $("#tns-remarks").val(answer["remarks"]);

          $("#productList").val(answer["productlist"]);

          $(".enlisted_products").empty();

          // Reload products table - restore all button to Green
          // After the data is retrieved - selected products turned Red (Deactivated)
          $(".transactionProductsTable").DataTable().ajax.reload();
          
          var data_items = new FormData();
          data_items.append("debnumber", debnumber);
          $.ajax({
            url:"ajax/debris_get_items.ajax.php",
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
              addingTotalPrices();
            }
          }); 
          
          let debstatus = $("#txt-debstatus").val();
          switch (debstatus){
            case 'Cancelled':
              $("#btn-save").prop('disabled', true);
              $("#btn-cancel").prop('disabled', true);
              break; 
            case 'Posted':
              $("#btn-save").prop('disabled', false);
              $("#btn-cancel").prop('disabled', false);
              break;                                          
          }        
          
          let prodnumber = $("#txt-prodnumber").val();
          if (prodnumber != ''){
            $("#btn-save").hide();
            $("#btn-cancel").hide();
          }else{
            $("#btn-save").show();
            $("#btn-cancel").show();
          }

          $("#btn-print").prop('disabled', false);
          $("#trans_type").val("Update"); 
          $("#modal-search-debris").modal('hide');
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
          listItems();
          $('.qty').focus();
        }
     })
  });

  // Input QTY or PRICE
  $(".debris-form").on("keydown keypress blur focus", "input.qty,input.price", function(){
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
    listItems(); 
  });  

  $(".debris-form").on("keydown keypress blur focus", "input.qty", function(){
      var itemid = $(this).parent().parent().children(".qtyEntry").children().attr("itemid");
      _gblBindNumericClasses('numeric'); 
      listItems(); 
  })

  var idRemoveProduct = [];
  localStorage.removeItem("removeProduct");
  $(".debris-form").on("click", "button.removeProduct", function(){
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
  });
  
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
      // var netamount = addingTotalPrice.toFixed(2) - $('#num-discount').val();
      // $("#num-netamount").val(numberWithCommas(netamount.toFixed(2)));
    }else{
      // $("#num-amount,#num-discount,#num-netamount").val('0.00');
      $("#num-amount").val('0.00');
    }   
  }  

  function listItems(){
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

        // Check if Qty
        if ((txt_qty == "0.00")||!(txt_qty)){  
          var hasZeroQty = true;
        }

        // // Check if Qty or Price = 0.00
        // if ((txt_qty == "0.00")||!(txt_qty)||(txt_price == "0.00")){  
        //   var hasZeroQty = true;
        // }

        var qty = parseFloat(txt_qty.replaceAll(",",""));
        var price = parseFloat(txt_price.replaceAll(",",""));
        var tamount = parseFloat(txt_tamount.replaceAll(",",""));

        productList.push({"qty" : qty.toFixed(2),
                          "price" : price.toFixed(2),
                          "tamount" : tamount.toFixed(2),
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

  $(".debris-form").submit(function (e) {
      e.preventDefault();
      swal.fire({
          title: 'Do you want to save waste/damages material?',
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
            let machineid = $("#sel-machineid").val();
            let debrisby = $("#sel-debrisby").val();

            let format_debdate = $("#date-debdate").val().split("/");
            format_debdate = format_debdate[2] + "-" + format_debdate[0] + "-" + format_debdate[1];
            
            let debdate = format_debdate;
            let debnumber = $("#txt-debnumber").val();
            let shift = $("#sel-shift").val();
            let debstatus = $("#txt-debstatus").val();
            let remarks = $("#tns-remarks").val();
            let postedby = $("#tns-postedby").val();
            let productlist = $("#productList").val();           

            var debrisencode = new FormData();
            debrisencode.append("trans_type", trans_type);
            debrisencode.append("machineid", machineid);
            debrisencode.append("debrisby", debrisby);
            debrisencode.append("debdate", debdate);
            debrisencode.append("debnumber", debnumber);
            debrisencode.append("shift", shift);
            debrisencode.append("debstatus", debstatus);
            debrisencode.append("remarks", remarks);
            debrisencode.append("postedby", postedby);
            debrisencode.append("productlist", productlist);
           
            $.ajax({
               url:"ajax/debris_save_record.ajax.php",
               method: "POST",
               data: debrisencode,
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
                    title: 'Waste/Damages successfully saved!',
                    type: 'success',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 1500
                });
                initialize();
                //  swal.fire({
                //     title: 'Waste and Damages successfully saved!',
                //     type: 'success',
                //     confirmButtonText: 'Proceed',
                //     confirmButtonClass: 'btn btn-outline-success',
                //     allowOutsideClick: false,
                //     buttonsStyling: false
                //  }).then(function(result){
                //     if(result.value) {              
                //       $("#btn-new").click();
                //     }
                //  }) 
               }
            })
          }
      });
  }); 
  
  $("#btn-new").click(function(){
      initialize();
  });  

  function initialize(){
    $("#sel-machineid").val('').trigger('change');
    $("#sel-debrisby").val('').trigger('change');
    $("#txt-debnumber").val("");
    $("#txt-debstatus").val("Posted");
    $("#txt-prodnumber").val("");
    $("#tns-remarks").val("");
    $("#num-amount").val("0.00");

    if ($("#trans_type").val() == 'Update'){
      $("#sel-shift").val('').trigger('change');
      $("#date-debdate").val("");
    }

    $('.enlisted_products').empty();

    $(".transactionProductsTable tbody button").each(function() {
        $(this).removeClass("btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2");
        $(this).addClass("btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct");
    });

    localStorage.removeItem("removeProduct");
    $(".transactionProductsTable").DataTable().ajax.reload();

    $("#btn-save").show();
    $("#btn-cancel").show();

    $("#btn-save").prop('disabled', true);
    $("#btn-cancel").prop('disabled', true);
    $("#btn-print").prop('disabled', true);

    $("#trans_type").val("New");
  }    
  
   // Cancel Waste/Damage
   $("#btn-cancel").click(function(){
      swal.fire({
          title: 'Do you want to Cancel waste/damage materials?',
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
            var debnumber = $("#txt-debnumber").val();           
            var canceldebris = new FormData();
            canceldebris.append("debnumber", debnumber);            
            $.ajax({
               url:"ajax/debris_cancel_record.ajax.php",
               method: "POST",
               data: canceldebris,
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
});    