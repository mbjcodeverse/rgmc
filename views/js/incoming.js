$('.transactionProductsTable').DataTable({
    ajax: "ajax/list_purchase_items_products.ajax.php",
    autoWidth: true,
    deferRender: true,
    processing: true,
    scrollY: 430,
    pagelength: 25,
    lengthMenu: [[25, 50, -1], [25, 50, "All"]],
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"p>',    //ip - Showing # of entries + Pagination - datatable footer
            language: {
                search: '<span>Filter:</span> _INPUT_',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
            }
});

if (!$.fn.DataTable.isDataTable('.incomingTransactionTable')) {
  var it = $('.incomingTransactionTable').DataTable({
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

if (!$.fn.DataTable.isDataTable('.purchaseorderTransactionTable')) {
  var pt = $('.purchaseorderTransactionTable').DataTable({
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
   $('#inc_date_range').daterangepicker({
     ranges:{
       'Today'         : [moment(),moment()],
       'Yesterday'     : [moment().subtract(1,'days'), moment().subtract(1,'days')],
       'Last 7 Days'   : [moment().subtract(6,'days'), moment()],
       'This Month'    : [moment().startOf('month'), moment().endOf('month')]
     }
   });

   $('#lst_date_range').daterangepicker({
    ranges:{
      'Today'         : [moment(),moment()],
      'Yesterday'     : [moment().subtract(1,'days'), moment().subtract(1,'days')],
      'Last 7 Days'   : [moment().subtract(6,'days'), moment()],
      'This Month'    : [moment().startOf('month'), moment().endOf('month')]
    }
   });

   $('.incoming-form input[id^="txt"]').on("keypress", function (e) {
      return _helper.isString(e) ? true : e.preventDefault();
   });

   $('.incoming-form input[id^="tns"]').on("keypress", function (e) {
      return _helper.allChars(e) ? true : e.preventDefault();
   });

  // Disable products table - wait for PO or Incoming to be loaded
  $(".transactionProductsTable *").attr('disabled', 'disabled');

  // Add Products
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
           let meas = answer["meas1"];
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
  $(".incoming-form").on("keydown keypress blur focus", "input.qty,input.price", function(){
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
  $(".incoming-form").on("click", "button.removeProduct", function(){
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
    
    // var a = document.getElementById("product_list");
    // var rows = a.rows.length;
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
        $("#btn-post").prop('disabled', true);
      }else{
        $("#btn-post").prop('disabled', false);
      }
    }else{
      $("#btn-post").prop('disabled', true);
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

   $("#btn-post").click(function(){
      $('#txt-delstatus').val('Posted');
   });   

   // SAVE Incoming
   $(".incoming-form").submit(function (e) {
      e.preventDefault();

      var title = "Do you want to POST incoming stock transaction?";
      var text = "You won't be able to change content of this transaction.";

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

            let format_deldate = $("#date-deldate").val().split("/");
            format_deldate = format_deldate[2] + "-" + format_deldate[0] + "-" + format_deldate[1];

            var ponumber = $("#txt-ponumber").val();
            // var suppliercode = $("#tns-suppliercode").val();
            var deldate = format_deldate;
            var delstatus = $("#txt-delstatus").val();
            var inctype = $("#sel-inctype").val();
            var iscode = $("#tns-iscode").val();
            var delnumber = $("#tns-delnumber").val();
            var checkedby = $("#sel-checkedby").val();
            var deliveredby = $("#tns-deliveredby").val();
            var postedby = $("#tns-postedby").val();
            var remarks = $("#tns-remarks").val();
            var txt_amount = $("#num-amount").val();
            var txt_discount = $("#num-discount").val();
            var txt_netamount = $("#num-netamount").val();
            var productlist = $("#productList").val();

            // Remove comma on values
            var amount = parseFloat(txt_amount.replaceAll(",",""));
            var discount= parseFloat(txt_discount.replaceAll(",",""));
            var netamount= parseFloat(txt_netamount.replaceAll(",",""));            

            var incoming = new FormData();
            incoming.append("trans_type", trans_type);

            incoming.append("ponumber", ponumber);
            incoming.append("deldate", deldate);
            incoming.append("delstatus", delstatus);
            incoming.append("inctype", inctype);
            incoming.append("iscode", iscode);
            incoming.append("delnumber", delnumber);
            incoming.append("checkedby", checkedby);
            incoming.append("deliveredby", deliveredby);
            incoming.append("postedby", postedby);
            incoming.append("remarks", remarks);
            incoming.append("amount", amount);
            incoming.append("discount", discount);
            incoming.append("netamount", netamount);
            incoming.append("productlist", productlist);            
            $.ajax({
               url:"ajax/incoming_save_record.ajax.php",
               method: "POST",
               data: incoming,
               cache: false,
               contentType: false,
               processData: false,
               dataType:"text",
               success:function(answer){
                  let delnumber = answer;
                  window.open("extensions/tcpdf/pdf/incoming.php?delnumber="+delnumber, "_blank");
               },
               error: function () {
                  alert("Oops. Something went wrong!");
               },
               complete: function () {
                 swal.fire({
                    title: 'Incoming transaction successfully saved!',
                    type: 'success',
                    confirmButtonText: 'Proceed',
                    confirmButtonClass: 'btn btn-outline-success',
                    allowOutsideClick: false,
                    buttonsStyling: false
                 }).then(function(result){
                    if(result.value) {              
                      window.location = 'incoming';
                    }
                 }) 
               }
            })
          }
      });
   });

   // When browsing Purchase Order - set Status to Pending | Partial
   // This will prevent AJAX from running twice
   $("#btn-search-po").click(function(){
      $(".purchaseorderTransactionTable").DataTable().clear();
      pt.draw();
      $('#lst-postatus').val('Pending | Partial').trigger('change');
   });      

   $("#lbl-lst-machineid").click(function(){
      $("#lst-machineid").val('').trigger('change');
   }); 

   $("#lbl-lst-suppliercode").click(function(){
      $("#lst-suppliercode").val('').trigger('change');
   });

   $("#lbl-lst-postatus").click(function(){
      $("#lst-postatus").val('Pending | Partial').trigger('change');
   });  

   // When browsing Incoming - set Status to Posted
   // This will prevent AJAX from running twice
   $("#btn-search").click(function(){
      $(".incomingTransactionTable").DataTable().clear();
      it.draw();
      $('#inc-delstatus').val('Posted').trigger('change');
   });    

   $("#lbl-inc-machineid").click(function(){
      $("#inc-machineid").val('').trigger('change');
   }); 

   $("#lbl-inc-suppliercode").click(function(){
      $("#inc-suppliercode").val('').trigger('change');
   });   

   $("#lbl-inc-delstatus").click(function(){
      $("#inc-delstatus").val('Posted').trigger('change');
   });       

   // SEARCH PO - Modal Form dynamic selector
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
           url:"ajax/purchaseorder_transaction_list.ajax.php",   
           method: "POST",                
           data: data,                    
           cache: false,                  
           contentType: false,            
           processData: false,            
           dataType:"json",               
           success:function(answer){
                $(".purchaseorderTransactionTable").DataTable().clear();
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

   // SEARCH Incoming - Modal Form dynamic selector
   $('#inc-machineid, #inc_date_range, #inc-suppliercode, #inc-delstatus').on("change", function(){
      let machineid = $("#inc-machineid").val();

      let date_range = $("#inc_date_range").val();
      let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
      let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);

      let suppliercode = $("#inc-suppliercode").val();
      let delstatus = $("#inc-delstatus").val();

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
      data.append("delstatus", delstatus);       

      $.ajax({
           url:"ajax/incoming_transaction_list.ajax.php",   
           method: "POST",                
           data: data,                    
           cache: false,                  
           contentType: false,            
           processData: false,           
           dataType:"json",                          
           success:function(answer){
                // alert(answer.length);
                $(".incomingTransactionTable").DataTable().clear();                
                for(var i = 0; i < answer.length; i++) {
                  percent = Math.round(i/answer.length*100);

                  var options = {
                    text: percent + "% complete."
                  };

                  let inc = answer[i];
                  let inc_date = inc.deldate;
                  let deldate = inc_date.split("-");
                  deldate = deldate[1] + "/" + deldate[2] + "/" + deldate[0];

                  let name = inc.name;
                  let delnumber = inc.delnumber;
                  let machinedesc = inc.machinedesc;
                  let delstatus = inc.delstatus;
                  let netamount = numberWithCommas(inc.netamount);

                  var button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnEditIncoming' delnumber='"+delnumber+"'><i class='icon-pencil3'></i></button></td>";  
                  it.row.add([deldate, name, delnumber, machinedesc, delstatus, netamount, button]);
               }
               it.draw();

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
   $(".purchaseorderTransactionTable tbody").on('click', '.btnEditPurchaseOrder', function () {
      var ponumber = $(this).attr("ponumber");
      $("#txt-delstatus").val("Posted");
      var data = new FormData();
      data.append("ponumber", ponumber);
      $.ajax({
         url:"ajax/purchaseorder_get_record_for_incoming.ajax.php",
         method: "POST",
         data: data,
         cache: false,
         contentType: false,
         processData: false,
         dataType:"json",
         success:function(answer){
            // Enable controls
            $("#date-deldate").prop('disabled', false);
            $("#tns-iscode").prop('disabled', false);
            $("#sel-inctype").prop('disabled', false);
            $("#sel-checkedby").prop('disabled', false);
            $("#tns-deliveredby").prop('disabled', false);
            $("#tns-remarks").prop('disabled', false);
            $("#num-discount").prop('disabled', false);
            $("#btn-viewpo").prop('disabled', false);
 
            let name = answer["name"];
            $("#txt-name").val(name.toUpperCase());
            $("#txt-ponumber").val(answer["ponumber"]);
            // $("#tns-suppliercode").val(answer["suppliercode"]);
            $("#txt-machinedesc").val(answer["machinedesc"]);

            // Clear controls
            $("#date-deldate").val('');
            $("#tns-iscode").val('');
            $("#tns-delnumber").val('');
            $("#sel-inctype").val('').trigger('change');
            $("#sel-checkedby").val('').trigger('change');
            $("#tns-deliveredby").val('');
            $("#tns-remarks").val('');

            $("#num-amount").val('0.00');
            $("#num-discount").val('0.00');
            $("#num-netamount").val('0.00');

            $("#productList").val(answer["productlist"]);
            $(".enlisted_products").empty();

            // Reload products table - restore all button to Green
            // After the data is retrieved - selected products turned Red (Deactivated)
            $(".transactionProductsTable").DataTable().ajax.reload();
            
            var data_items = new FormData();
            data_items.append("ponumber", ponumber);
            $.ajax({
               url:"ajax/purchaseorder_get_items.ajax.php",
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

                   var qty = '0.00';
                   var price = numberWithCommas(product.price);
                   var tamount = '0.00';

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

            $("#btn-view").prop('disabled', false);
            $("#trans_type").val("New"); 
            $("#modal-search-purchaseorder").modal('hide');
         }
      });
   });   

   // Get Incoming Record
   $(".incomingTransactionTable tbody").on('click', '.btnEditIncoming', function () {
      var delnumber = $(this).attr("delnumber");
      var data = new FormData();
      data.append("delnumber", delnumber);
      $.ajax({
         url:"ajax/incoming_get_record.ajax.php",
         method: "POST",
         data: data,
         cache: false,
         contentType: false,
         processData: false,
         dataType:"json",
         success:function(answer){
            // Enable controls
            $("#date-deldate").prop('disabled', false);
            $("#tns-iscode").prop('disabled', false);
            $("#sel-inctype").prop('disabled', false);
            $("#sel-checkedby").prop('disabled', false);
            $("#tns-deliveredby").prop('disabled', false);
            $("#tns-remarks").prop('disabled', false);
            $("#num-discount").prop('disabled', false);
            $("#btn-viewpo").prop('disabled', false);
 
            let name = answer["name"];
            $("#txt-name").val(name.toUpperCase());
            $("#txt-ponumber").val(answer["ponumber"]);
            // $("#tns-suppliercode").val(answer["suppliercode"]);
            $("#txt-machinedesc").val(answer["machinedesc"]);

            let deldate = answer["deldate"].split("-");
            deldate = deldate[1] + "/" + deldate[2] + "/" + deldate[0];
            if (deldate == '00/00/0000'){      
              deldate = '';
            }

            $("#date-deldate").val(deldate);
            $("#txt-delstatus").val(answer["delstatus"]);
            $("#sel-inctype").val(answer["inctype"]).trigger('change');
            $("#tns-iscode").val(answer["iscode"]);
            $("#tns-delnumber").val(answer["delnumber"]);
            $("#sel-checkedby").val(answer["checkedby"]).trigger('change');
            $("#tns-deliveredby").val(answer["deliveredby"]);
            $("#tns-postedby").val(answer["postedby"]);
            $("#tns-remarks").val(answer["remarks"]);

            $("#num-amount").val(numberWithCommas(answer["amount"]));
            $("#num-discount").val(numberWithCommas(answer["discount"]));
            $("#num-netamount").val(numberWithCommas(answer["netamount"]));

            $("#productList").val(answer["productlist"]);
            $(".enlisted_products").empty();

            // Reload products table - restore all button to Green
            // After the data is retrieved - selected products turned Red (Deactivated)

            $(".transactionProductsTable *").attr('disabled', 'disabled');  
            
            var data_items = new FormData();
            data_items.append("delnumber", delnumber);
            $.ajax({
               url:"ajax/incoming_get_items.ajax.php",
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
                //  if ($('#txt-delstatus').val() == 'Deferred'){                  
                //     removeAddedProducts();
                //  }
               }
            });

            let delstatus = $("#txt-delstatus").val();
            switch (delstatus){
              case 'Cancelled':
                $("#date-deldate").prop('disabled', true);
                $("#tns-iscode").prop('disabled', true);
                $("#sel-inctype").prop('disabled', true);
                $("#sel-checkedby").prop('disabled', true);
                $("#tns-deliveredby").prop('disabled', true);
                $("#tns-remarks").prop('disabled', true);
                $("#num-discount").prop('disabled', true);

                // $("#btn-defer").prop('disabled', true);
                $("#btn-post").prop('disabled', true);
                break; 
              case 'Posted':
                $("#date-deldate").prop('disabled', true);
                $("#tns-iscode").prop('disabled', true);
                $("#sel-inctype").prop('disabled', true);
                $("#sel-checkedby").prop('disabled', true);
                $("#tns-deliveredby").prop('disabled', true);
                $("#tns-remarks").prop('disabled', true);
                $("#num-discount").prop('disabled', true);

                // $(".transactionProductsTable *").attr('disabled', 'disabled');

                // $("#btn-defer").prop('disabled', true);
                $("#btn-post").prop('disabled', true);
                break;                                          
            }            

            $("#btn-print").prop('disabled', false);
            $("#btn-view").prop('disabled', false);
            $("#trans_type").val("Update"); 
            $("#modal-search-incoming").modal('hide');
         }
      });
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
          $("#purchase-inc-qty").empty();

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
              $("#purchase-inc-qty").append(
                '<tr>'+   
                  '<td style="text-align:left;">'+pdesc+'</td>'+ 
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
              txt_inc_qty = '';                                                 
            }
          }
          $("#purchase-inc-qty").append(
            '<tr>'+   
              '<td style="text-align:left;">'+pdesc+'</td>'+ 
              '<td style="text-align:right;">'+txt_pur_qty+'</td>'+
              '<td style="text-align:right;">'+last_inc_qty+'</td>'+                               
            '</tr>');
        }
      })
   });     

   // Cancel Incoming
   $("#btn-cancel").click(function(){
      swal.fire({
          title: 'Do you want to Cancel incoming stock transaction?',
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
            var delnumber = $("#tns-delnumber").val();            
            var cancelincoming = new FormData();
            cancelincoming.append("ponumber", ponumber);
            cancelincoming.append("delnumber", delnumber);            
            $.ajax({
               url:"ajax/incoming_cancel_record.ajax.php",
               method: "POST",
               data: cancelincoming,
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
                    confirmButtonText: 'Got it!',
                    confirmButtonClass: 'btn btn-outline-success',
                    allowOutsideClick: false,
                    buttonsStyling: false
                 }).then(function(result){
                    if(result.value) {              
                      window.location = 'incoming';
                    }
                 })
               },
               complete: function () {
                 swal.fire({
                    title: 'Cancellation Successful!',
                    type: 'success',
                    confirmButtonText: 'Got it!',
                    confirmButtonClass: 'btn btn-outline-success',
                    allowOutsideClick: false,
                    buttonsStyling: false
                 }).then(function(result){
                    if(result.value) {              
                      window.location = 'incoming';
                    }
                 })
               }

            })
          }
      });
   });

   // Print Incoming
   $("#btn-print").click(function(){
      var delnumber = $("#tns-delnumber").val();
      window.open("extensions/tcpdf/pdf/incoming.php?delnumber="+delnumber, "_blank");
   });   

   function disableAddProducts(){
      $("button.recoverButton").removeClass("btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct");
      $("button.recoverButton").addClass("btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2");
   } 

   function enableAddProducts(){
      $("button.recoverButton").addClass('btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2');
      $("button.recoverButton").removeClass('btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct');
   }    

});



