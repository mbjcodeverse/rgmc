// Subcomponents
$('.transactionProductsTable').DataTable({
    ajax: "ajax/list_all_components.ajax.php",
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

$('.transactionFinishedGoodsTable').DataTable({
    ajax: "ajax/list_all_products.ajax.php",
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

if (!$.fn.DataTable.isDataTable('.prodcapacityTransactionTable')) {
  var pt = $('.prodcapacityTransactionTable').DataTable({
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
    toggleTable();

    $('#sel-etype').change(function() {
        toggleTable();
    });

    function toggleTable() {
        $('.enlisted_products').empty();
        $("#productList").val("");
        if ($('#sel-etype').val() === 'Subcomponents') {
            $('.subcomponents_table').show();
            let tableSubcomponents = $('.transactionProductsTable').DataTable();
            tableSubcomponents.columns.adjust();
            $('.finishedgoods_table').hide();

            $(".transactionProductsTable tbody button").each(function() {
            $(this).removeClass("btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2");
            $(this).addClass("btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct");
        });
        } else {
            $('.finishedgoods_table').show();
            let tableFinishedGoods = $('.transactionFinishedGoodsTable').DataTable();
            tableFinishedGoods.columns.adjust();
            $('.subcomponents_table').hide();

            $(".transactionFinishedGoodsTable tbody button").each(function() {
            $(this).removeClass("btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2");
            $(this).addClass("btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct");
        });
        }
    }

    $("#btn-new").click(function(){
      initialize();
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
        //   var price_amount = answer["ucost"];
        //   var price = numberWithCommas(price_amount);

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
            
            '<td class="shiftEntry" width="15%" style="padding:2px;">'+
                '<input type="text" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control shiftgoal numeric" itemid="'+itemid+'" name="shiftgoal" value="0.00" required>'+
            '</td>' +   

            '<td class="packEntry" width="15%" style="padding:2px;">'+
                '<input type="text" style="padding:2px;padding-right:17px;text-align:right;" class="form-control packtarget" name="packtarget" value="0.00" readonly required>'+
            '</td>' +
          '</tr>')

        //   addingTotalPrices();
        //   listItems();
          $('.qty').focus();
        }
     })
    });

    $(".transactionFinishedGoodsTable tbody").on("click", "button.addProduct", function(){
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
        //   var price_amount = answer["ucost"];
        //   var price = numberWithCommas(price_amount);

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
                
                '<td class="shiftEntry" width="15%" style="padding:2px;">'+
                    '<input type="text" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control shiftgoal numeric" itemid="'+itemid+'" name="shiftgoal" value="0.00" required>'+
                '</td>' +   

                '<td class="packEntry" width="15%" style="padding:2px;">'+
                    '<input type="text" style="padding:2px;padding-right:17px;text-align:right;" class="form-control packtarget" name="packtarget" value="0.00" readonly required>'+
                '</td>' +
            '</tr>')

        //   addingTotalPrices();
        //   listItems();
          $('.qty').focus();
        }
     })
    });    

    // Input QTY or SHIFT GOAL
    $(".prodcapacity-form").on("keydown keypress blur focus", "input.qty,input.shiftgoal", function(){
        var itemid = $(this).parent().parent().children(".qtyEntry").children().attr("itemid");

        var q = $(this).parent().parent().children(".qtyEntry").children().val();
        var quantity = q.replaceAll(",","");

        var sg = $(this).parent().parent().children(".shiftEntry").children().val();
        var shiftgoal = sg.replaceAll(",","");   

        if (quantity != 0.00){
            var packEntry = shiftgoal / quantity;
        }else{
            var packEntry = 0.00;
        }
        
        var productAmount = $(this).parent().parent().children(".packEntry").children(".packtarget");
        productAmount.val(numberWithCommas(packEntry.toFixed(2)));

        _gblBindNumericClasses('numeric'); 
        listItems(); 
    });

    // function listItems(){
    //   var productList = [];
    //   var description = $(".pdesc");
    //   var quantity = $(".qty");
    //   var shiftgoal = $(".shiftgoal");
    //   var packtarget = $(".packtarget");

    //   var hasZeroQty = false;

    //   var num_entries = description.length; 
    //   if (num_entries > 0){
    //    for(var i = 0; i < num_entries; i++){
    //     var txt_qty = $(quantity[i]).val();
    //     var txt_shiftgoal = $(shiftgoal[i]).val();
    //     var txt_packtarget = $(packtarget[i]).val();

    //     if ((txt_qty == "0.00")||!(txt_qty)){  
    //       var hasZeroQty = true;
    //     }

    //     var qty = parseFloat(txt_qty.replaceAll(",",""));
    //     var shiftgoal = parseFloat(txt_shiftgoal.replaceAll(",",""));
    //     var packtarget = parseFloat(txt_packtarget.replaceAll(",",""));

    //     productList.push({"qty" : qty.toFixed(2),
    //                       "shiftgoal" : shiftgoal.toFixed(2),
    //                       "packtarget" : packtarget.toFixed(2),
    //                       "itemid" : $(description[i]).attr("itemid")})      
    //    }

    //    $("#productList").val(JSON.stringify(productList));

    //    if (hasZeroQty){
    //      $("#btn-save").prop('disabled', true);
    //    }else{
    //      $("#btn-save").prop('disabled', false);
    //    }
    //   }else{
    //    $("#btn-save").prop('disabled', true);
    //  }
    // } 

    function listItems() {
        var productList = [];
        var hasZeroQty = false;

        $(".prodcapacity-form .pdesc").each(function() {
            var row = $(this).closest("tr"); // Get the closest row (tr) to the current element
            var itemid = $(this).attr("itemid");

            var qty = row.find(".qty").val().replaceAll(",", "");
            var shiftgoal = row.find(".shiftgoal").val().replaceAll(",", "");
            var packtarget = row.find(".packtarget").val().replaceAll(",", "");

            // If qty is 0, mark hasZeroQty
            if ((qty == "0.00" || !qty) || (shiftgoal == "0.00" || !shiftgoal)) {
                hasZeroQty = true;
            }

            // Push the item details into the productList
            productList.push({
                "qty": parseFloat(qty).toFixed(2),
                "shiftgoal": parseFloat(shiftgoal).toFixed(2),
                "packtarget": parseFloat(packtarget).toFixed(2),
                "itemid": itemid
            });
        });

        // Update the hidden input with the product list as JSON
        $("#productList").val(JSON.stringify(productList));

        // Enable or disable the save button based on zero quantity
        if (hasZeroQty) {
            $("#btn-save").prop('disabled', true);
        } else {
            $("#btn-save").prop('disabled', false);
        }
    }

    var idRemoveProduct = [];
    localStorage.removeItem("removeProduct");
    $(".prodcapacity-form").on("click", "button.removeProduct", function(){
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
    }); 
    
    // ******** Subcomponents *********
    $(".transactionProductsTable").on("draw.dt", function(){
        if(localStorage.getItem("removeProduct") != null){
            var listIdProducts = JSON.parse(localStorage.getItem("removeProduct"));
            for(var i = 0; i < listIdProducts.length; i++){
                $("button.recoverButton[itemid='"+listIdProducts[i]["itemid"]+"']").removeClass('btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2');
                $("button.recoverButton[itemid='"+listIdProducts[i]["itemid"]+"']").addClass('tn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct');
            }
        }
    })  

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
    
    // ******** Finished Goods *********
    $(".transactionFinishedGoodsTable").on("draw.dt", function(){
        if(localStorage.getItem("removeProduct") != null){
            var listIdProducts = JSON.parse(localStorage.getItem("removeProduct"));
            for(var i = 0; i < listIdProducts.length; i++){
                $("button.recoverButton[itemid='"+listIdProducts[i]["itemid"]+"']").removeClass('btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2');
                $("button.recoverButton[itemid='"+listIdProducts[i]["itemid"]+"']").addClass('tn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct');
            }
        }
    })  

    function removeFinishedGoods(){
        var itemid = $(".removeProduct");     
        var tableButtons = $(".transactionFinishedGoodsTable tbody button.addProduct");
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

    $('.transactionFinishedGoodsTable').on('draw.dt', function(){
        removeFinishedGoods();
    });    

    function initialize(){
        $("#sel-machineid").val('').trigger('change');
        $("#txt-capacitynumber").val("");
        $("#tns-remarks").val("");

        $('.enlisted_products').empty();
        $("#productList").val("");

        $(".transactionProductsTable tbody button").each(function() {
            $(this).removeClass("btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2");
            $(this).addClass("btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct");
        });

        $(".transactionFinishedGoodsTable tbody button").each(function() {
            $(this).removeClass("btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2");
            $(this).addClass("btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct");
        });

        localStorage.removeItem("removeProduct");
        $(".transactionProductsTable").DataTable().ajax.reload();
        $(".transactionFinishedGoodsTable").DataTable().ajax.reload();

        $("#btn-save").prop('disabled', true);
        $("#btn-cancel").prop('disabled', true);
        $("#btn-print").prop('disabled', true);

        // $("#sel-etype").val('Finished Goods').trigger('change');

        $("#trans_type").val("New");
    }

    $(".prodcapacity-form").submit(function (e) {
        // alert($("#productList").val());
        e.preventDefault();
        swal.fire({
            title: 'Do you want to save machine capacity tracker?',
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
                let etype = $("#sel-etype").val();
                let capacitynumber = $("#txt-capacitynumber").val();
                let remarks = $("#tns-remarks").val();
                let postedby = $("#tns-postedby").val();
                let productlist = $("#productList").val();     
                // alert(productlist);

                var capacitytracker = new FormData();
                capacitytracker.append("trans_type", trans_type);
                capacitytracker.append("machineid", machineid);
                capacitytracker.append("etype", etype);
                capacitytracker.append("capacitynumber", capacitynumber);
                capacitytracker.append("remarks", remarks);
                capacitytracker.append("postedby", postedby);
                capacitytracker.append("productlist", productlist);
            
                $.ajax({
                    url:"ajax/prodcapacity_save_record.ajax.php",
                    method: "POST",
                    data: capacitytracker,
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
                            title: 'Machine capacity tracker successfully saved!',
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

    // *********** Search ************
    $("#lbl-lst-classcode").click(function(){
      $("#lst-classcode").val('').trigger('change');
    });

    $("#lbl-lst-etype").click(function(){
      $("#lst-etype").val('').trigger('change');
    });
    
    $('#lst-classcode, #lst-etype').on("change", function(){
        let classcode = $("#lst-classcode").val();
        let etype = $("#lst-etype").val();

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
        data.append("classcode", classcode);
        data.append("etype", etype);

        $.ajax({
            url:"ajax/prodcapacity_transaction_list.ajax.php",   
            method: "POST",                
            data: data,                    
            cache: false,                  
            contentType: false,            
            processData: false,            
            dataType:"json",               
            success:function(answer){
                $(".prodcapacityTransactionTable").DataTable().clear();
                for(var i = 0; i < answer.length; i++) {
                    percent = Math.round(i/answer.length*100);
                    var options = {
                    text: percent + "% complete."
                    };

                    let mc = answer[i];
                    let capacitynumber = mc.capacitynumber;
                    let machinedesc = mc.machinedesc;
                    let machineid = mc.machineid;
                    let etype = mc.etype;
                    
                    var button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnEditProdcapacity' capacitynumber='"+capacitynumber+"'><i class='icon-pencil3'></i></button></td>";  
                    pt.row.add([machinedesc, etype, button]); 
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

    $("#btn-search").click(function(){
        $(".prodcapacityTransactionTable").DataTable().clear();
        pt.draw();
        $("#lst-classcode").val('').trigger('change');
    }); 
    
    $(".prodcapacityTransactionTable tbody").on('click', '.btnEditProdcapacity', function () {
        var capacitynumber = $(this).attr("capacitynumber");
        var data = new FormData();
        data.append("capacitynumber", capacitynumber);
        $.ajax({
        url:"ajax/prodcapacity_get_record.ajax.php",
        method: "POST",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        dataType:"json",
        success:function(answer){
            $("#sel-machineid").val(answer["machineid"]).trigger('change');
            $("#txt-capacitynumber").val(answer["capacitynumber"]);
            $("#sel-etype").val(answer["etype"]).trigger('change');
            $("#tns-remarks").val(answer["remarks"]);     // debris

            $("#productList").val(answer["productlist"]);
            $(".enlisted_products").empty();

            // Reload items - restore all button to Green
            // After the data is retrieved - selected items turned Red (Deactivated)
            $(".transactionProductsTable").DataTable().ajax.reload();
            $(".transactionFinishedGoodsTable").DataTable().ajax.reload();

            var etype = answer["etype"];
            
            var data_items = new FormData();
            data_items.append("capacitynumber", capacitynumber);
            data_items.append("etype", etype);
            $.ajax({
                url:"ajax/prodcapacity_get_items.ajax.php",
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
                    var shiftgoal = numberWithCommas(product.shiftgoal);
                    var packtarget = numberWithCommas(product.packtarget);

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
                            
                            '<td class="shiftEntry" width="15%" style="padding:2px;">'+
                                '<input type="text" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control shiftgoal numeric" itemid="'+itemid+'" name="shiftgoal" value="'+shiftgoal+'" required>'+
                            '</td>' +   

                            '<td class="packEntry" width="15%" style="padding:2px;">'+
                                '<input type="text" style="padding:2px;padding-right:17px;text-align:right;" class="form-control packtarget" name="packtarget" value="'+packtarget+'" readonly required>'+
                            '</td>' +
                        '</tr>')
                }               
                removeAddedProducts();
                removeFinishedGoods();
                }
            });         
            
            //   let prodstatus = $("#txt-prodstatus").val();
            //   switch (prodstatus){
            //     case 'Cancelled':
            //       $("#btn-save").prop('disabled', true);
            //       $("#btn-cancel").prop('disabled', true);
            //       break; 
            //     case 'Posted':
            //       $("#btn-save").prop('disabled', false);
            //       $("#btn-cancel").prop('disabled', false);
            //       break;                                          
            //   }          

            $("#btn-print").prop('disabled', false);
            $("#trans_type").val("Update"); 
            $("#modal-search-prodcapacity").modal('hide');
        }
        });
    });    
});    