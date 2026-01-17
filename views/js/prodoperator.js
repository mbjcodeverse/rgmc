$('.transactionWasteTable').DataTable({
    ajax: "ajax/list_all_wastedamage.ajax.php",
    autoWidth: true,
    deferRender: true,
    processing: true,
    scrollY: 431,
    pagelength: 25,
    lengthMenu: [[25, 50, -1], [25, 50, "All"]],
    dom: '<"datatable-header"><"datatable-scroll"t><"datatable-footer"fp>',    //ip - Showing # of entries + Pagination - datatable footer
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

$('.transactionProductsTable').DataTable({
    ajax: "ajax/list_all_products.ajax.php",
    autoWidth: true,
    deferRender: true,
    processing: true,
    scrollY: 431,
    pagelength: 25,
    lengthMenu: [[25, 50, -1], [25, 50, "All"]],
    dom: '<"datatable-header"><"datatable-scroll"t><"datatable-footer"fp>',    //ip - Showing # of entries + Pagination - datatable footer
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

$('.transactionSubcomponentsTable').DataTable({
    ajax: "ajax/list_all_components.ajax.php",
    autoWidth: true,
    deferRender: true,
    processing: true,
    scrollY: 431,
    pagelength: 25,
    lengthMenu: [[25, 50, -1], [25, 50, "All"]],
    dom: '<"datatable-header"><"datatable-scroll"t><"datatable-footer"fp>',    //ip - Showing # of entries + Pagination - datatable footer
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

$('.transactionRecycleTable').DataTable({
    ajax: "ajax/list_all_rawmats.ajax.php",
    autoWidth: true,
    deferRender: true,
    processing: true,
    scrollY: 431,
    pagelength: 25,
    lengthMenu: [[25, 50, -1], [25, 50, "All"]],
    dom: '<"datatable-header"><"datatable-scroll"t><"datatable-footer"fp>',    //ip - Showing # of entries + Pagination - datatable footer
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

if (!$.fn.DataTable.isDataTable('.prodoperatorTransactionTable')) {
  var pt = $('.prodoperatorTransactionTable').DataTable({
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
  // let operator = $("#tns-postedby").val();
  // $('#sel-operatedby').val(operator).trigger('change');
  // $('#lst-empid').val(operator).trigger('change');

  $('#date-proddate').daterangepicker({
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
      'All'           : [moment('2025-06-01'), moment()],
    },
    startDate: moment().startOf('month'), 
    endDate: moment().endOf('month'),
    minDate: moment('2025-06-30')
  })

   // When browsing Purchase Order - set Status to Pending | Partial
   // This will prevent AJAX from running twice
  $("#btn-search").click(function(){
    $(".prodfinTransactionTable").DataTable().clear();
    pt.draw();
    $('#lst-prodstatus').val('Posted').trigger('change');
  });   

  $("#lbl-lst-machineid").click(function(){
      $("#lst-machineid").val('').trigger('change');
  }); 

  $("#lbl-lst-empid").click(function(){
      $("#lst-empid").val('').trigger('change');
  });

  $("#lbl-lst-prodstatus").click(function(){
      $("#lst-prodstatus").val('').trigger('change');
  });   
  
  $("#lbl-lst-etype").click(function(){
      $("#lst-etype").val('').trigger('change');
  });  

  $("#btn-undo").click(function(){
      $('#sel-machineid').val('').trigger('change');
  });   

  $("#btn-new").click(function(){
      initialize();
  });

  $("#btn-report").click(function(){
      let etype = $("#sel-etype").val();
      if (etype == 'Finished Goods'){
        window.location = 'prodfinreport';
      }else if (etype == 'Subcomponents'){
        window.location = 'prodcomreport';
      }else{
        window.location = 'recyclereport';
      }
  });


  // Search Products - Modal Form dynamic selector
  $('#lst-machineid, #lst_date_range, #lst-empid, #lst-prodstatus, #lst-etype').on("change", function(){
    let machineid = $("#lst-machineid").val();

    let date_range = $("#lst_date_range").val();
    let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
    let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);

    let empid = $("#lst-empid").val();
    let prodstatus = $("#lst-prodstatus").val();
    let etype = $("#lst-etype").val();

    let user_level = $("#user_level").val();
    let prod_opr = $("#prod_opr").val();    // check if user allowed to edit, also view all operator entries

    if (prod_opr == 'Full'){
      var postedby = "";
    }else{
      var postedby = $("#tns-postedby").val();
    }
    // if (user_level == 'Operator'){
    //   var postedby = $("#tns-postedby").val();
    // }else{
    //   var postedby = "";
    // }

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
    data.append("prodstatus", prodstatus);
    data.append("etype", etype);
    data.append("postedby", postedby);

    $.ajax({
        // url:"ajax/prodfin_transaction_list.ajax.php", 
        url:"ajax/prodoperator_transaction_list.ajax.php",  
        method: "POST",                
        data: data,                    
        cache: false,                  
        contentType: false,            
        processData: false,            
        dataType:"json",               
        success:function(answer){
              $(".prodoperatorTransactionTable").DataTable().clear();
              for(var i = 0; i < answer.length; i++) {
                percent = Math.round(i/answer.length*100);
                var options = {
                  text: percent + "% complete."
                };

                let sw = answer[i];
                let req_date = sw.proddate;
                let proddate = req_date.split("-");
                proddate = proddate[1] + "/" + proddate[2] + "/" + proddate[0];

                let operator = sw.operated_by;
                let prodnumber = sw.prodnumber;
                let shift = sw.shift;
                let machinedesc = sw.machinedesc;
                let machabbr = sw.machabbr;
                let prodstatus = sw.prodstatus;
                let etype = sw.etype;
                let total_amount = numberWithCommas(sw.total_amount);

                if (machinedesc != ''){
                  var machine = machinedesc + ' (' + machabbr + ')';
                }else{
                  var machine = '';
                }

                var button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnEditProdopr' prodnumber='"+prodnumber+"' etype='"+etype+"'><i class='icon-pencil3'></i></button></td>";  
                pt.row.add([proddate, operator, prodnumber, shift, machine, etype, total_amount, button]); 
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

  // Get Product
  $(".prodoperatorTransactionTable tbody").on('click', '.btnEditProdopr', function () {
    var prodnumber = $(this).attr("prodnumber");
    var etype = $(this).attr("etype");
    var data = new FormData();
    data.append("prodnumber", prodnumber);
    data.append("etype", etype);
    $.ajax({
      // url:"ajax/prodfin_get_record.ajax.php",
      url:"ajax/prodoperator_get_record.ajax.php",
      method: "POST",
      data: data,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(answer){
          $("#sel-operatedby").val(answer["operatedby"]).trigger('change');

          let proddate = answer["proddate"].split("-");
          proddate = proddate[1] + "/" + proddate[2] + "/" + proddate[0];
          if (proddate == '00/00/0000'){      
            proddate = '';
          }
          $("#date-proddate").val(proddate);
          $("#txt-prodstatus").val(answer["prodstatus"]);
          $("#txt-debstatus").val(answer["debstatus"]);

          $("#txt-prodnumber").val(answer["prodnumber"]);

          $("#txt-debnumber").val(answer["debnumber"]);     // debris
          var debnumber = answer["debnumber"];              // use to get debris items

          $("#sel-shift").val(answer["shift"]).trigger('change');
          $("#sel-etype").val(etype).trigger('change');
          $("#sel-etype").prop('disabled', true);

          let machineid = answer["machineid"];
          if (machineid != ''){
            $("#sel-machineid").val(answer["machineid"]).trigger('change');
          }else{
            $("#sel-machineid").val("").trigger('change');
          }

          $("#tns-remarks").val(answer["remarks"]);
          $("#tns-wremarks").val(answer["wremarks"]);

          $("#productList").val(answer["productlist"]);

          if (answer["debstatus"] == 'Posted'){
            $("#wasteList").val(answer["wastelist"]);     // debris
          }else{
            $("#wasteList").val(answer[""]);
          }

          $(".enlisted_products").empty();
          $(".enlisted_waste").empty();

          // Reload products table - restore all button to Green
          // After the data is retrieved - selected products turned Red (Deactivated)
          $(".transactionProductsTable").DataTable().ajax.reload();
          $(".transactionWasteTable").DataTable().ajax.reload();
          
          var data_items = new FormData();
          data_items.append("prodnumber", prodnumber);
          data_items.append("etype", etype);
          $.ajax({
            // url:"ajax/prodfin_get_items.ajax.php",
            url:"ajax/prodoperator_get_items.ajax.php",
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
              removeAddedSubcomponents();
              removeAddedRecycle();
              addingTotalPrices();
            }
          });

          var data_witems = new FormData();
          data_witems.append("debnumber", debnumber);
          $.ajax({
            // url:"ajax/waste_get_items.ajax.php",
            url:"ajax/waste_operator_get_items.ajax.php",
            method: "POST",
            data: data_witems,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(products){
              for(var p = 0; p < products.length; p++) {
                var product = products[p];
                var itemid = product.itemid;
                var iclass = product.iclass;

                var product_name = product.pdesc;
                var meas_2 = product.meas2;

                var meas2 = meas_2.toUpperCase();
                var pdesc = product_name + ' (' + meas2 + ')';

                var qty = numberWithCommas(product.qty);
                var price = numberWithCommas(product.price);
                var tamount = numberWithCommas(product.tamount);

                $(".enlisted_waste").append(
                    '<tr>'+   
                    '<td width="50%" style="padding:2px;">'+   
                    '<div class="input-group">'+
                        '<span style="padding:2px;" class="input-group-prepend"><button type="button" style="color:coral;" class="btn btn-sm btn-light removeWproduct" itemid="'+itemid+'"><i class="icon-undo"></i></button></span>'+         
                        '<input type="text" style="padding:2px;" class="form-control wpdesc" itemid="'+itemid+'" name="addProduct" value="'+pdesc+'" readonly required>'+
                        '</div>'+
                    '</td>'+       
                    
                    // '<td class="itemClass" width="15%" style="padding:2px;">'+
                    //   '<select data-placeholder="< Status >" class="form-control select-search iclass" name="iclass" value="'+iclass+'">'+
                    //       '<option value="W">Waste</option>'+
                    //       '<option value="D">Damage</option>'+
                    //   '<select>'+
                    // '</td>' + 

                    '<td class="itemClass" width="15%" style="padding:2px;">'+
                      '<select data-placeholder="< Status >" class="form-control select-search iclass" name="iclass" itemid="'+itemid+'">'+
                          '<option value="W" ' + (iclass === 'W' ? 'selected' : '') + ' style="background-color: #2a3141; color: #ffffff;">Waste</option>'+  // Check and add selected dynamically
                          '<option value="D" ' + (iclass === 'D' ? 'selected' : '') + ' style="background-color: #2a3141; color: #ffffff;">Damage</option>'+  // Check and add selected dynamically
                      '</select>'+
                    '</td>' + 

                    '<td class="wqtyEntry" width="15%" style="padding:2px;">'+
                    '<input type="text" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control wqty numeric" itemid="'+itemid+'" name="wqty" value="'+qty+'" required>'+
                    '</td>' +  
                    
                    '<td class="wpriceEntry" width="15%" style="padding:2px;">'+
                        '<input type="text" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control wprice numeric" itemid="'+itemid+'" name="wprice" value="'+price+'" required>'+
                    '</td>' +   

                    '<td class="wtotalAmount" width="15%" style="padding:2px;">'+
                        '<input type="text" style="padding:2px;padding-right:17px;text-align:right;" class="form-control wtamount" productPrice="'+price+'" name="wtamount" value="'+tamount+'" readonly required>'+
                    '</td>' +
                '</tr>');
              }
              // listProducts();                   
              // removeAddedWaste();
              addingTotalWaste();
            }
          });          
          
          let prodstatus = $("#txt-prodstatus").val();
          let prod_opr = $("#prod_opr").val();    // check if user is allowed to edit - see mopr in access rights
          switch (prodstatus){
            case 'Cancelled':
              $("#btn-save").prop('disabled', true);
              $("#btn-cancel").prop('disabled', true);
              break; 
            case 'Posted':
              if (prod_opr == 'Full'){
                $("#btn-save").prop('disabled', false);
              }else{
                $("#btn-save").prop('disabled', true);
              }
              $("#btn-cancel").prop('disabled', false);
              break;                                          
          }          

          $("#btn-print").prop('disabled', false);
          $("#trans_type").val("Update"); 
          $("#modal-search-prodoperator").modal('hide');
      }
    });
  });  

  // APPEND PRODUCT - Finished Goods
  $(".transactionProductsTable tbody").on("click", "button.addProduct", function(){
     var itemid = $(this).attr("itemid");
  
     $(this).removeClass("btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct");
     $(this).addClass("btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2");

     var data = new FormData();
     data.append("itemid", itemid);
     $.ajax({
      url:"ajax/get_goods_details.ajax.php", 
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

  // APPEND PRODUCT - Subcomponents
  $(".transactionSubcomponentsTable tbody").on("click", "button.addProduct", function(){
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

  // APPEND PRODUCT - Recycling
  $(".transactionRecycleTable tbody").on("click", "button.addProduct", function(){
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
  $(".prodoperator-form").on("keydown keypress blur focus", "input.qty,input.price", function(){
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

  $(".prodoperator-form").on("keydown keypress blur focus", "input.qty", function(){
      var itemid = $(this).parent().parent().children(".qtyEntry").children().attr("itemid");
      _gblBindNumericClasses('numeric'); 
      listItems(); 
  })

  var idRemoveProduct = [];
  localStorage.removeItem("removeProduct");
  $(".prodoperator-form").on("click", "button.removeProduct", function(){
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

  // FINISHED GOODS --------------------------------------
  $(".transactionProductsTable").on("draw.dt", function(){
     if(localStorage.getItem("removeProduct") != null){
      var listIdProducts = JSON.parse(localStorage.getItem("removeProduct"));
      for(var i = 0; i < listIdProducts.length; i++){
        $("button.recoverButton[itemid='"+listIdProducts[i]["itemid"]+"']").removeClass('btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2');
        $("button.recoverButton[itemid='"+listIdProducts[i]["itemid"]+"']").addClass('tn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct');
      }
     }
  })  

  // Remove Added Finished Goods
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

  // SUBCOMPONENTS --------------------------------------------
  $(".transactionSubcomponentsTable").on("draw.dt", function(){
     if(localStorage.getItem("removeProduct") != null){
      var listIdProducts = JSON.parse(localStorage.getItem("removeProduct"));
      for(var i = 0; i < listIdProducts.length; i++){
        $("button.recoverButton[itemid='"+listIdProducts[i]["itemid"]+"']").removeClass('btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2');
        $("button.recoverButton[itemid='"+listIdProducts[i]["itemid"]+"']").addClass('tn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct');
      }
     }
  })  

  // Remove Added Subcomponents
  function removeAddedSubcomponents(){
     var itemid = $(".removeProduct");     
     var tableButtons = $(".transactionSubcomponentsTable tbody button.addProduct");
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

  $('.transactionSubcomponentsTable').on('draw.dt', function(){
    removeAddedSubcomponents();
  });

  // RECYCLED --------------------------------------------
  $(".transactionRecycleTable").on("draw.dt", function(){
     if(localStorage.getItem("removeProduct") != null){
      var listIdProducts = JSON.parse(localStorage.getItem("removeProduct"));
      for(var i = 0; i < listIdProducts.length; i++){
        $("button.recoverButton[itemid='"+listIdProducts[i]["itemid"]+"']").removeClass('btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2');
        $("button.recoverButton[itemid='"+listIdProducts[i]["itemid"]+"']").addClass('tn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct');
      }
     }
  })  

  // Remove Added Recycle
  function removeAddedRecycle(){
     var itemid = $(".removeProduct");     
     var tableButtons = $(".transactionRecycleTable tbody button.addProduct");
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

  $('.transactionRecycleTable').on('draw.dt', function(){
    removeAddedRecycle();
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
          let prod_opr = $("#prod_opr").val();  
          let trans_type = $("#trans_type").val();
          if (prod_opr == 'Full' || trans_type == 'New'){
            $("#btn-save").prop('disabled', false);
          }else{
            $("#btn-save").prop('disabled', true);
          }
       }
      }else{
       $("#btn-save").prop('disabled', true);
     }
  } 

  function initialize(){
    $("#sel-etype").val('Finished Goods').trigger('change');
    $("#sel-etype").prop('disabled', false);
    $("#sel-machineid").val('').trigger('change');
    // $("#sel-shift").val('').trigger('change');
    $("#sel-operatedby").val('').trigger('change');
    $("#txt-prodnumber").val("");
    $("#txt-prodstatus").val("Posted");
    $("#txt-debstatus").val("Posted");

    $("#tns-remarks").val("");
    $("#tns-wremarks").val("");

    $("#num-amount").val("0.00");
    $("#num-wamount").val("0.00");

    if ($("#trans_type").val() == 'Update'){
      $("#sel-shift").val('').trigger('change');
      // $("#date-proddate").val("");
    }

    $('.enlisted_products').empty();
    $("#productList").val("");
    $('.enlisted_waste').empty();
    $("#wasteList").val("");
    // $('.enlisted_subcomponents').empty();
    // $("#subcomponentsList").val("");

    $(".transactionProductsTable tbody button").each(function() {
        $(this).removeClass("btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2");
        $(this).addClass("btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct");
    });

    $(".transactionWasteTable tbody button").each(function() {
        $(this).removeClass("btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2");
        $(this).addClass("btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct");
    });

    localStorage.removeItem("removeProduct");
    $(".transactionProductsTable").DataTable().ajax.reload();

    localStorage.removeItem("removeWproduct");
    $(".transactionWasteTable").DataTable().ajax.reload();

    $("#btn-save").prop('disabled', true);
    $("#btn-cancel").prop('disabled', true);
    $("#btn-print").prop('disabled', true);

    $("#trans_type").val("New");
  }

  $(".prodoperator-form").submit(function (e) {
      e.preventDefault();
      swal.fire({
          title: 'Do you want to save final product?',
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
            let operatedby = $("#sel-operatedby").val();

            let format_proddate = $("#date-proddate").val().split("/");
            format_proddate = format_proddate[2] + "-" + format_proddate[0] + "-" + format_proddate[1];
            
            let proddate = format_proddate;
            let prodnumber = $("#txt-prodnumber").val();
            let shift = $("#sel-shift").val();
            let prodstatus = $("#txt-prodstatus").val();
            let remarks = $("#tns-remarks").val();
            let postedby = $("#tns-postedby").val();
            let productlist = $("#productList").val();     
            
            let debnumber = $("#txt-debnumber").val();
            let debstatus = $("#txt-debstatus").val();
            let wremarks = $("#tns-wremarks").val();
            let wastelist = $("#wasteList").val();

            var finalproduct = new FormData();
            finalproduct.append("trans_type", trans_type);
            finalproduct.append("machineid", machineid);
            finalproduct.append("operatedby", operatedby);
            finalproduct.append("proddate", proddate);
            finalproduct.append("prodnumber", prodnumber);
            finalproduct.append("shift", shift);
            finalproduct.append("prodstatus", prodstatus);
            finalproduct.append("remarks", remarks);
            finalproduct.append("postedby", postedby);
            finalproduct.append("productlist", productlist);

            finalproduct.append("debnumber", debnumber);
            finalproduct.append("debstatus", debstatus);
            finalproduct.append("wremarks", wremarks);
            finalproduct.append("wastelist", wastelist);
           
            $.ajax({
               url:"ajax/prodoperator_save_record.ajax.php",
               method: "POST",
               data: finalproduct,
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
                    title: 'Final product successfully saved!',
                    type: 'success',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 1500
                });
                initialize();
                //  swal.fire({
                //     title: 'Final product successfully saved!',
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
  
  // Cancel Production
  $("#btn-cancel").click(function(){
      swal.fire({
          title: 'Do you want to Cancel final production transaction?',
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
            var prodnumber = $("#txt-prodnumber").val();            
            var cancelprodfin = new FormData();
            cancelprodfin.append("prodnumber", prodnumber);            
            $.ajax({
               url:"ajax/prodfin_cancel_record.ajax.php",
               method: "POST",
               data: cancelprodfin,
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






    // ------------------------------ Waste Entries ---------------------------------
    // Initially set visibility based on the selected value
    toggleTable();

    // When the selection changes
    $('#sel-etype').change(function() {
        toggleTable();
    });

    // Function to toggle the visibility of the tables
    function toggleTable() {
        $(".transactionProductsTable").DataTable().ajax.reload();
        $(".transactionSubcomponentsTable").DataTable().ajax.reload();
        $(".transactionRecycleTable").DataTable().ajax.reload();

        // $('.enlisted_products').empty();
        // $('.enlisted_waste').empty();
        // $('.enlisted_subcomponents').empty();

        if ($('#sel-etype').val() === 'Finished Goods') {
            $('.enlisted_products').empty();
            $('.products_table').show();
            let tableProduction = $('.transactionProductsTable').DataTable();
            tableProduction.columns.adjust();
            $('.subcomponents_table').hide();
            $('.recycle_table').hide();
            $('.waste_table').hide();
            $("#btn-wastedamage").prop('disabled', false);
            $("#num-amount").val("0.00");
        } else if ($('#sel-etype').val() === 'Subcomponents'){
            $('.enlisted_products').empty();
            $('.subcomponents_table').show();
            let tableSubcomponents = $('.transactionSubcomponentsTable').DataTable();
            tableSubcomponents.columns.adjust();
            $('.products_table').hide();
            $('.recycle_table').hide();
            $('.waste_table').hide();
            $("#btn-wastedamage").prop('disabled', false);
            $("#num-amount").val("0.00");
        } else if ($('#sel-etype').val() === 'Recycle'){
            $('.enlisted_products').empty();
            $('.enlisted_waste').empty();
            $('.recycle_table').show();
            let tableRecyle = $('.transactionRecyleTable').DataTable();
            tableRecyle.columns.adjust();
            $('.products_table').hide();
            $('.subcomponents_table').hide();
            $('.waste_table').hide();   
            $("#btn-wastedamage").prop('disabled', true); 
            $("#num-amount").val("0.00");
            $("#num-wamount").val("0.00");
        } 
        // else {
        //     $('.waste_table').show();
        //     let tableWaste = $('.transactionWasteTable').DataTable();
        //     tableWaste.columns.adjust();
        //     $('.products_table').hide();
        //     $('.subcomponents_table').hide();
        //     $('.recycle_table').hide();
        // }
    }

    $("#btn-wastedamage").click(function(){
        $('.waste_table').show();
        let tableWaste = $('.transactionWasteTable').DataTable();
        tableWaste.columns.adjust();
        $('.products_table').hide();
        $('.subcomponents_table').hide();
        $('.recycle_table').hide();
    });

    $('#btn-wastedamage').on('contextmenu', function (e) {
        e.preventDefault();
        $(".transactionProductsTable").DataTable().ajax.reload();
        $(".transactionSubcomponentsTable").DataTable().ajax.reload();
        $(".transactionRecycleTable").DataTable().ajax.reload();

        // $('.enlisted_products').empty();
        // $('.enlisted_waste').empty();
        // $('.enlisted_subcomponents').empty();

        if ($('#sel-etype').val() === 'Finished Goods') {
            $('.products_table').show();
            let tableProduction = $('.transactionProductsTable').DataTable();
            tableProduction.columns.adjust();
            $('.subcomponents_table').hide();
            $('.recycle_table').hide();
            $('.waste_table').hide();
        } else if ($('#sel-etype').val() === 'Subcomponents'){
            $('.subcomponents_table').show();
            let tableSubcomponents = $('.transactionSubcomponentsTable').DataTable();
            tableSubcomponents.columns.adjust();
            $('.products_table').hide();
            $('.recycle_table').hide();
            $('.waste_table').hide();
        } else if ($('#sel-etype').val() === 'Recycle'){
            $('.recycle_table').show();
            let tableRecyle = $('.transactionRecyleTable').DataTable();
            tableRecyle.columns.adjust();
            $('.products_table').hide();
            $('.subcomponents_table').hide();
            $('.waste_table').hide();    
        } 
    });

    $(".transactionWasteTable tbody").on("click", "button.addProduct", function(){
        let itemid = $(this).attr("itemid");
    
        // $(this).removeClass("btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct");
        // $(this).addClass("btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2");

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

                // // Check if a row with the same wpdesc and iclass already exists
                // let isDuplicate = false;
                // $(".enlisted_waste tr").each(function() {
                //     var existingPdesc = $(this).find(".wpdesc").val(); // Get the description of existing rows
                //     var existingIclass = $(this).find(".iclass").val(); // Get the iclass of existing rows

                //     // If both wpdesc and iclass match, mark as duplicate
                //     if (existingPdesc === pdesc && existingIclass === "W") { // Assuming "W" is the default status to check
                //         isDuplicate = true;
                //         return false; // Exit the loop early
                //     }
                // });

                // // Prevent adding the duplicate row if found
                // if (isDuplicate) {
                //     alert("This product is already listed with the same status.");
                //     return; // Prevent further processing
                // }

                $(".enlisted_waste").append(
                '<tr>'+   
                  '<td width="50%" style="padding:2px;">'+   
                  '<div class="input-group">'+
                      '<span style="padding:2px;" class="input-group-prepend"><button type="button" style="color:coral;" class="btn btn-sm btn-light removeWproduct" itemid="'+itemid+'"><i class="icon-undo"></i></button></span>'+         
                      '<input type="text" style="padding:2px;" class="form-control wpdesc" itemid="'+itemid+'" name="addProduct" value="'+pdesc+'" readonly required>'+
                      '</div>'+
                  '</td>'+      
                  
                  '<td class="itemClass" width="15%" style="padding:2px;">'+
                    '<select data-placeholder="< Status >" class="form-control select-search iclass" name="iclass">'+
                        '<option value="W" style="background-color: #2a3141; color: #ffffff;">Waste</option>'+
                        '<option value="D" style="background-color: #2a3141; color: #ffffff;">Damage</option>'+
                    '<select>'+
                  '</td>' + 

                  '<td class="wqtyEntry" width="15%" style="padding:2px;">'+
                      '<input type="text" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control wqty numeric" itemid="'+itemid+'" name="wqty" value="0.00" required>'+
                  '</td>' +  
                  
                  '<td class="wpriceEntry" width="15%" style="padding:2px;">'+
                      '<input type="text" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control wprice numeric" itemid="'+itemid+'" name="wprice" value="'+price+'" required>'+
                  '</td>' +   

                  '<td class="wtotalAmount" width="15%" style="padding:2px;">'+
                      '<input type="text" style="padding:2px;padding-right:17px;text-align:right;" class="form-control wtamount" productPrice="'+price+'" name="wtamount" value="0.00" readonly required>'+
                  '</td>' +
                '</tr>')

                addingTotalWaste();
                listWasteItems();
                $('.wqty').focus();
            }
        });
    });

    $(".prodoperator-form").on("change", ".iclass", function(){
        listWasteItems(); 
    }); 

    // Input QTY or PRICE
    $(".prodoperator-form").on("keydown keypress blur focus", "input.wqty,input.wprice", function(){
        var itemid = $(this).parent().parent().children(".wqtyEntry").children().attr("itemid");

        var wq = $(this).parent().parent().children(".wqtyEntry").children().val();
        var wquantity = wq.replaceAll(",","");

        var wp = $(this).parent().parent().children(".wpriceEntry").children().val();
        var wprice = wp.replaceAll(",","");   

        var wtotalAmount = wquantity * wprice;
        
        var wproductAmount = $(this).parent().parent().children(".wtotalAmount").children(".wtamount");
        wproductAmount.val(numberWithCommas(wtotalAmount.toFixed(2)));

        _gblBindNumericClasses('numeric'); 

        addingTotalWaste();
        listWasteItems(); 
    });

    $(".prodoperator-form").on("keydown keypress blur focus", "input.wqty", function(){
        var itemid = $(this).parent().parent().children(".wqtyEntry").children().attr("itemid");
        _gblBindNumericClasses('numeric'); 
        listWasteItems(); 
    });

    var idRemoveWproduct = [];
    localStorage.removeItem("removeWproduct");
    $(".prodoperator-form").on("click", "button.removeWproduct", function(){
        $(this).parent().parent().parent().parent().remove();
        var itemid = $(this).attr("itemid");

        if(localStorage.getItem("removeWproduct") == null){
            idRemoveWproduct = [];
        }else{
            idRemoveWproduct.concat(localStorage.getItem("removeWproduct"))
        }

        idRemoveWproduct.push({"itemid":itemid});
        localStorage.setItem("removeWproduct", JSON.stringify(idRemoveWproduct));

        // $("button.recoverButton[itemid='"+itemid+"']").removeClass('btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2');
        // $("button.recoverButton[itemid='"+itemid+"']").addClass('btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct');

        addingTotalWaste();
        listWasteItems();
        
        var a = document.getElementById("waste_list");
        var rows = a.rows.length;
    }); 

    function addingTotalWaste(){
        var priceItem = $(".wtamount");
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
            $("#num-wamount").val(numberWithCommas(addingTotalPrice.toFixed(2)));
        }else{
            $("#num-wamount").val('0.00');
        }   
    }  
    
    function listWasteItems(){
        var wasteList = [];
        var description = $(".wpdesc");
        var item_class = $(".iclass");
        var quantity = $(".wqty");
        var priceamount = $(".wprice");
        var totalamount = $(".wtamount");

        var hasNoneQty = false;

        var num_entries = description.length; 
        if (num_entries > 0){
            for(var i = 0; i < num_entries; i++){
                var txt_qty = $(quantity[i]).val();
                var txt_price = $(priceamount[i]).val();
                var txt_tamount = $(totalamount[i]).val();

                if ((txt_qty == "0.00")||!(txt_qty)){  
                    var hasNoneQty = true;
                }

                var iclass = $(item_class[i]).val();
                var qty = parseFloat(txt_qty.replaceAll(",",""));
                var price = parseFloat(txt_price.replaceAll(",",""));
                var tamount = parseFloat(txt_tamount.replaceAll(",",""));

                wasteList.push({"iclass" : iclass,
                                "qty" : qty.toFixed(2),
                                "price" : price.toFixed(2),
                                "tamount" : tamount.toFixed(2),
                                "itemid" : $(description[i]).attr("itemid")})      
            }

            $("#wasteList").val(JSON.stringify(wasteList));

            let productList = $("#productList").val();
            if (hasNoneQty || productList == ''){
                $("#btn-save").prop('disabled', true);
            }else{
                let prod_opr = $("#prod_opr").val();  
                let trans_type = $("#trans_type").val();
                if (prod_opr == 'Full' || trans_type == 'New'){
                  $("#btn-save").prop('disabled', false);
                }else{
                  $("#btn-save").prop('disabled', true);
                }
            }
        }else{
            $("#wasteList").val('');
            $("#tns-wremarks").val('');
            $("#btn-save").prop('disabled', false);
        }
    }     

    // ----- SAVE ROUTINE -----
    $(".prodoperator-form").submit(function (e) {
      e.preventDefault();
      swal.fire({
          title: 'Do you want to save production?',
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
            let etype = $("#sel-etype").val();
            let machineid = $("#sel-machineid").val();
            let operatedby = $("#sel-operatedby").val();

            let format_proddate = $("#date-proddate").val().split("/");
            format_proddate = format_proddate[2] + "-" + format_proddate[0] + "-" + format_proddate[1];
            
            let proddate = format_proddate;
            let prodnumber = $("#txt-prodnumber").val();
            let shift = $("#sel-shift").val();
            let prodstatus = $("#txt-prodstatus").val();
            let remarks = $("#tns-remarks").val();
            let postedby = $("#tns-postedby").val();
            let productlist = $("#productList").val();     
            
            let debnumber = $("#txt-debnumber").val();
            let debstatus = $("#txt-debstatus").val();
            let wremarks = $("#tns-wremarks").val();
            let wastelist = $("#wasteList").val();

            var operatorproduct = new FormData();
            operatorproduct.append("trans_type", trans_type);
            operatorproduct.append("etype", etype);
            operatorproduct.append("machineid", machineid);
            operatorproduct.append("operatedby", operatedby);
            operatorproduct.append("proddate", proddate);
            operatorproduct.append("prodnumber", prodnumber);
            operatorproduct.append("shift", shift);
            operatorproduct.append("prodstatus", prodstatus);
            operatorproduct.append("remarks", remarks);
            operatorproduct.append("postedby", postedby);
            operatorproduct.append("productlist", productlist);

            operatorproduct.append("debnumber", debnumber);
            operatorproduct.append("debstatus", debstatus);
            operatorproduct.append("wremarks", wremarks);
            operatorproduct.append("wastelist", wastelist);
           
            $.ajax({
               url:"ajax/prodoperator_save_record.ajax.php",
               method: "POST",
               data: operatorproduct,
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
                    title: 'Production successfully saved!',
                    type: 'success',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 1500
                });
                initialize();
               }
            })
          }
      });
  });
});    