
var pl = $('.transactionProductsTable').DataTable({
    ajax: "ajax/list_all_items_with_onhand.ajax.php",
    autoWidth: true,
    deferRender: true,
    processing: true,
    scrollY: 431,
    pagelength: 25,
    lengthMenu: [[25, 50, -1], [25, 50, "All"]],
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"p>',
            language: {
                search: '<span>Filter:</span> _INPUT_',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
            }
});

if (!$.fn.DataTable.isDataTable('.releasingTransactionTable')) {
  var pt = $('.releasingTransactionTable').DataTable({
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

if (!$.fn.DataTable.isDataTable('.machine_trackingListTable')) {
    var mtl = $('.machine_trackingListTable').DataTable({
        deferRender: true,
        processing: true,
        autoWidth: true,
        scrollY: 360,
        pageLength: 25,
        lengthMenu: [[25, 50], [25, 50]],
        dom: '<"datatable-header"><"extra-row"> <"datatable-scroll"t><"datatable-footer"fp>',
        language: {
            loadingRecords: 'Please wait - loading...',
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
            search: '<span>Filter:</span> _INPUT_',
            searchPlaceholder: 'Type to filter...',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 
                'first': 'First', 
                'last': 'Last', 
                'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 
                'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' 
            }
        }
    });
}

$(function() {
    // Search Releasing
    $('#lst_date_range').daterangepicker({
      startDate: moment('2025-09-01'),
      endDate: moment(),
      ranges: {
        'Today'       : [moment(), moment()],
        'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
        'This Month'  : [moment().startOf('month'), moment().endOf('month')],
        'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        'All'         : [moment('2025-09-01'), moment()]
      }
    });   

    // Search Job Order
    $('#list_date_range').daterangepicker({
      startDate: moment('2025-09-01'),
      endDate: moment(),
      ranges: {
        'Today'       : [moment(), moment()],
        'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
        'This Month'  : [moment().startOf('month'), moment().endOf('month')],
        'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        'All'         : [moment('2025-09-01'), moment()]
      }
    });  

    // Disable the select elements
    $('#sel-machineid').prop('disabled', true);
    $('#sel-deptcode').prop('disabled', true);
    $('#btn-undo').prop('disabled', true);

    $('#sel-reltype').on('change', function () {
      $('#sel-deptcode').val('').trigger('change');
      $('#sel-machineid').val('').trigger('change');

      $('#txt-inccode').val('');
      $('#txt-controlnum').val('');
      $('#txt-curstatus').val('');

      if ($(this).val() === 'Job Order') {
        $('#sel-machineid').prop('disabled', true);
        $('#sel-deptcode').prop('disabled', true);

        $('#sel-deptcode').prop('required', false);
        $('#sel-machineid').prop('required', false);

        if ($('#trans_type').val() == 'New'){
          $('#modal-search-machine_tracking').modal({
              backdrop: 'static',
              keyboard: false
          }).modal('show');
          loadMachineTrackingList();
        }
      }else if ($(this).val() === 'Generic') {
        $('#sel-machineid').prop('disabled', false);
        $('#sel-deptcode').prop('disabled', true);
        $('#sel-machineid').prop('required', true);
      }else{
        $('#sel-machineid').prop('disabled', true);
        $('#sel-deptcode').prop('disabled', false);
        $('#sel-deptcode').prop('required', true);
      }
    });

    // Monitor change in #sel-reltype
    // var isManualChange = true;
    // $('#sel-reltype').on('change', function () {
    //     if (isManualChange) {
    //       $('#sel-machineid').val('').trigger('change');
    //     }

    //     $('#sel-deptcode').val('').trigger('change');
    //     $('#txt-inccode').val('');
    //     $('#txt-controlnum').val('');
    //     $('#txt-curstatus').val('');

    //     if ($(this).val() === 'Job Order') {
    //         $('#sel-machineid').prop('disabled', true);
    //         $('#sel-deptcode').prop('disabled', true);

    //         if (isManualChange) {
    //             $('#modal-search-machine_tracking').modal({
    //                 backdrop: 'static',
    //                 keyboard: false
    //             }).modal('show');
    //             loadMachineTrackingList();
    //         }
    //     } else {
    //         $('#sel-machineid').prop('disabled', false);
    //         $('#sel-deptcode').prop('disabled', false);
    //     }
    // });

  // let isClearing = false;

  // $('#sel-machineid').on('change', function() {
  //   if (!isClearing) {
  //     isClearing = true;
  //     $('#sel-deptcode').val('').trigger('change');
  //     isClearing = false;
  //   }
  // });

  // var isLoadingData = false;
  // $('#sel-deptcode').on('change', function() {
  //   if (isLoadingData) return;
  //   if (!isClearing) {
  //     isClearing = true;
  //     //$('#sel-machineid').val('').trigger('change');
  //     isClearing = false;
  //   }
  // });  

  // When the modal is closed, set #sel-reltype to 'Generic'
  $('#modal-search-machine_tracking').on('hidden.bs.modal', function () {
      if ($("#sel-machineid").val() == ''){
        $('#sel-reltype').val('Generic').trigger('change');
      }
  });
  
  function loadMachineTrackingList() {
    let machineid = $("#lst-machine_id").val();
    if (machineid == null){
        machineid = '';
    }
    let datemode = $("#lst-datemode").val();

    var date_range = $("#list_date_range").val();
    if (date_range != ''){
        var start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
        var end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
    } else {
        var start_date = '';
        var end_date = '';
    }
    let curstatus = $("#lst-curstatus").val();

    var data = new FormData();
    data.append("machineid", machineid);
    data.append("datemode", datemode);
    data.append("start_date", start_date);
    data.append("end_date", end_date);
    data.append("curstatus", curstatus);

    $.ajax({
        url: "ajax/machine_tracking_list.ajax.php",
        method: "POST",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(answer) {
            $(".machine_trackingListTable").DataTable().clear();
            for (var i = 0; i < answer.length; i++) {
                var mt = answer[i];
                var datereported = mt.datereported;
                var inctime = mt.inctime;
                var inccode = mt.inccode;
                var controlnum = mt.controlnum;
                var machinedesc = mt.machinedesc;
                var curstatus = mt.curstatus;
                var datecompleted = mt.datecompleted;
                var button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnDiagnosis' inccode='" + inccode + "'><i class='icon-pencil3'></i></button></td>";
                mtl.row.add([datereported, inctime, controlnum, machinedesc, curstatus, datecompleted, button]);
            }
            mtl.draw();
        },
        complete: function() {
            $(".machine_trackingListTable td").css({
                "padding-top": "5px",
                "padding-bottom": "5px"
            });
        }
    });
  }

  // Attach change event handler to call the extracted function
  $('#lst-machine_id, #lst-datemode, #list_date_range, #lst-curstatus').on("change", function() {
    loadMachineTrackingList();
  });  

  $(".machine_trackingListTable").on("draw.dt", function () {
      $(".machine_trackingListTable td").css({
          "padding-top": "5px",
          "padding-bottom": "5px"
      });
  });  

  $(".machine_trackingListTable tbody").on("click", "button.btnDiagnosis", function(){
      var inccode = $(this).attr("inccode");
      var data = new FormData();
      data.append("inccode", inccode);
      $.ajax({
          url:"ajax/machine_tracking_get_record.ajax.php",
          method: "POST",
          data: data,
          cache: false,
          contentType: false,
          processData: false,
          dataType:"json",
          success:function(answer){
              $("#sel-machineid").val(answer["machineid"]).trigger('change');
              $("#txt-curstatus").val(answer["curstatus"]).trigger('change');
              $("#txt-controlnum").val(answer["controlnum"]);
              $("#txt-inccode").val(answer["inccode"]);

              $("#modal-search-machine_tracking").modal("hide");
          }
      })
  });   

  // When browsing Purchase Order - set Status to Pending | Partial
  // This will prevent AJAX from running twice
  $("#btn-search").click(function(){
    $(".releasingTransactionTable").DataTable().clear();
    pt.draw();
    $('#lst-reqstatus').val('Posted').trigger('change');
  });   

  $("#lbl-lst-machine_id").click(function(){
      $("#lst-machine_id").val('').trigger('change');
  }); 

  $("#lbl-lst-machineid").click(function(){
      $("#lst-machineid").val('').trigger('change');
  }); 

  $("#lbl-lst-empid").click(function(){
      $("#lst-empid").val('').trigger('change');
  });

  $("#lbl-lst-reqstatus").click(function(){
      $("#lst-reqstatus").val('').trigger('change');
  });   
  
  $("#lbl-lst-reltype").click(function(){
      $("#lst-reltype").val('').trigger('change');
  }); 

  $("#btn-undo").click(function(){
      $('#sel-machineid').val('').trigger('change');
  });  
  
  $("#lbl-lst-date-range").click(function(){
    $('#lst_date_range').data('daterangepicker').setStartDate(moment('2025-09-01'));
    $('#lst_date_range').data('daterangepicker').setEndDate(moment());
    
    mtl.search('').draw();
    mtl.table().container().querySelector('.dataTables_filter input').focus(); 
  });

  $("#lbl-lst-curstatus").click(function(){
      $("#lst-curstatus").val('').trigger('change');
  });
  
  // Hide Item ID column - Show modal form Stock Card
  // pl.column(2).visible(false);	
  // $('.transactionProductsTable tbody').on('dblclick', 'tr', function () {
  //   let idx = pl.row(this).index();  
  //   let itemid = pl.cell(idx, 2).data();			// get Item ID - hidden column  
  //   $('#stockcard').modal('show');      
  // });  

  // Search Stock Withdrawal - Modal Form dynamic selector
  $('#lst-machineid, #lst_date_range, #lst-empid, #lst-reqstatus, #lst-reltype').on("change", function(){
    let machineid = $("#lst-machineid").val();

    let date_range = $("#lst_date_range").val();
    let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
    let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);

    let empid = $("#lst-empid").val();
    let reqstatus = $("#lst-reqstatus").val();
    let reltype = $("#lst-reltype").val();

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
    data.append("reqstatus", reqstatus);
    data.append("reltype", reltype);

    $.ajax({
        url:"ajax/releasing_transaction_list.ajax.php",   
        method: "POST",                
        data: data,                    
        cache: false,                  
        contentType: false,            
        processData: false,            
        dataType:"json",               
        success:function(answer){
              $(".releasingTransactionTable").DataTable().clear();
              for(var i = 0; i < answer.length; i++) {
                percent = Math.round(i/answer.length*100);
                var options = {
                  text: percent + "% complete."
                };

                let sw = answer[i];
                let deptname = sw.deptname;
                let controlnum = sw.controlnum;  

                let req_date = sw.reqdate;
                let reqdate = req_date.split("-");
                reqdate = reqdate[1] + "/" + reqdate[2] + "/" + reqdate[0];

                let requestor = sw.request_by;
                let reqnumber = sw.reqnumber;
                let shift = sw.shift;
                let machinedesc = sw.machinedesc;
                let machabbr = sw.machabbr;
                let reqstatus = sw.reqstatus;
                let total_amount = numberWithCommas(sw.total_amount);

                if (machinedesc != ''){
                  var machine = machinedesc + ' (' + machabbr + ')';
                }else{
                  var machine = '';
                }

                var button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnEditReleasing' reqnumber='"+reqnumber+"'><i class='icon-pencil3'></i></button></td>";  
                pt.row.add([reqdate, requestor, reqnumber, shift, controlnum, machine, deptname, total_amount, button]); 
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
  $(".releasingTransactionTable tbody").on('click', '.btnEditReleasing', function () {
    $("#trans_type").val("Update"); 
    var reqnumber = $(this).attr("reqnumber");
    var data = new FormData();
    data.append("reqnumber", reqnumber);
    $.ajax({
      url:"ajax/releasing_get_record.ajax.php",
      method: "POST",
      data: data,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(answer){
          $("#sel-reltype").val(answer["reltype"]).trigger('change');
          $("#sel-machineid").val(answer["machineid"]).trigger('change');
          
          $("#sel-requestby").val(answer["requestby"]).trigger('change');

          let reqdate = answer["reqdate"].split("-");
          reqdate = reqdate[1] + "/" + reqdate[2] + "/" + reqdate[0];
          if (reqdate == '00/00/0000'){      
            reqdate = '';
          }
          $("#date-reqdate").val(reqdate);
          $("#txt-reqstatus").val(answer["reqstatus"]);
          $("#txt-reqnumber").val(answer["reqnumber"]);
          $("#sel-shift").val(answer["shift"]).trigger('change');
          

          // let machineid = answer["machineid"];
          // if (machineid != ''){
          //   $("#sel-machineid").val(machineid).trigger('change');
          //   alert(machineid);
          // }else{
          //   $("#sel-machineid").val("").trigger('change');
          // }

          let deptcode = answer["deptcode"];
          if (deptcode != ''){
            $("#sel-deptcode").val(answer["deptcode"]).trigger('change');
          }else{
            $("#sel-deptcode").val("").trigger('change');
          }         
          
          $("#txt-controlnum").val(answer["controlnum"]);
          $("#txt-curstatus").val(answer["curstatus"]);
          $("#txt-inccode").val(answer["inccode"]);

          $("#tns-remarks").val(answer["remarks"]);

          $("#productList").val(answer["productlist"]);

          $(".enlisted_products").empty();

          // Reload products table - restore all button to Green
          // After the data is retrieved - selected products turned Red (Deactivated)
          $(".transactionProductsTable").DataTable().ajax.reload();
          
          var data_items = new FormData();
          data_items.append("reqnumber", reqnumber);
          $.ajax({
            url:"ajax/releasing_get_items.ajax.php",
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
                var meas_1 = product.meas1;

                var meas1 = meas_1.toUpperCase();
                var pdesc = product_name + ' (' + meas1 + ')';

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

          $("#btn-print").prop('disabled', false);
          $("#btn-save").prop('disabled', false);
          // $("#trans_type").val("Update"); 
          $("#modal-search-releasing").modal('hide');
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
  $(".stockout-form").on("keydown keypress blur focus", "input.qty,input.price", function(){
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

  $(".stockout-form").on("keydown keypress blur focus", "input.qty", function(){
      var itemid = $(this).parent().parent().children(".qtyEntry").children().attr("itemid");
      _gblBindNumericClasses('numeric'); 
      listItems(); 
  })

  var idRemoveProduct = [];
  localStorage.removeItem("removeProduct");
  $(".stockout-form").on("click", "button.removeProduct", function(){
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

      // function additionArrayPrices(total, numberArray){
      //   return total + numberArray;
      // }
      // var addingTotalPrice = arrayAdditionPrice.reduce(additionArrayPrices);

      // $("#num-amount").val(numberWithCommas(addingTotalPrice.toFixed(2)));
      // var netamount = addingTotalPrice.toFixed(2) - $('#num-discount').val();
      // $("#num-netamount").val(numberWithCommas(netamount.toFixed(2)));
    }else{
      // $("#num-amount,#num-discount,#num-netamount").val('0.00');
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

  $(".stockout-form").submit(function (e) {
      e.preventDefault();
      swal.fire({
          title: 'Do you want to save stock withdrawal transaction?',
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
            let inccode = $("#txt-inccode").val();
            
            let reltype = $("#sel-reltype").val();
            let deptcode = $("#sel-deptcode").val();

            let machineid = $("#sel-machineid").val();
            let requestby = $("#sel-requestby").val();

            let format_reqdate = $("#date-reqdate").val().split("/");
            format_reqdate = format_reqdate[2] + "-" + format_reqdate[0] + "-" + format_reqdate[1];
            
            let reqdate = format_reqdate;
            let reqnumber = $("#txt-reqnumber").val();
            let shift = $("#sel-shift").val();
            let reqstatus = $("#txt-reqstatus").val();
            let remarks = $("#tns-remarks").val();
            let postedby = $("#tns-postedby").val();
            let productlist = $("#productList").val();           

            var stockout = new FormData();
            stockout.append("trans_type", trans_type);
            stockout.append("inccode", inccode);
            stockout.append("reltype", reltype);
            stockout.append("deptcode", deptcode);
            stockout.append("machineid", machineid);
            stockout.append("requestby", requestby);
            stockout.append("reqdate", reqdate);
            stockout.append("reqnumber", reqnumber);
            stockout.append("shift", shift);
            stockout.append("reqstatus", reqstatus);
            stockout.append("remarks", remarks);
            stockout.append("postedby", postedby);
            stockout.append("productlist", productlist);
           
            $.ajax({
               url:"ajax/stockout_save_record.ajax.php",
               method: "POST",
               data: stockout,
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
                    title: 'Stock withdrawal transaction successfully saved!',
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