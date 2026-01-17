
if (!$.fn.DataTable.isDataTable('.goodsTable')) {
  var fg = $('.goodsTable').DataTable({
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
   $(".select").select2({
      minimumResultsForSearch: Infinity,
   });

   $(".select-search").select2();

   $('#goods-form input[id^="num"]').on("keypress", function (e) {
      return _helper.isNumericDash(e) ? true : e.preventDefault();
   });

   $('#goods-form input[id^="txt"]').on("keypress", function (e) {
      return _helper.isString(e) ? true : e.preventDefault();
   });

   $('#goods-form input[id^="tns"]').on("keypress", function (e) {
      return _helper.allChars(e) ? true : e.preventDefault();
   });

   $("#btn-new").click(function(){
     $('#txt-itemid').val('');
     $('#tns-pdesc').val('');
     $("#sel-categorycode").val('').trigger('change');
     // $("#sel-brandcode").val('0').trigger('change');
     $("#chk-isactive").prop( "checked", true); 
     $("#sel-meas1").val('').trigger('change');
     $('#num-eqnum').val('1.00');
     $("#sel-meas2").val('').trigger('change');
     $('#tns-itemcode').val('');
     $('#num-ucost').val('0.00');
     $('#num-reorder').val('0.00');
     $('#num-pweight').val('0.00');
     $("#sel-wmeas").val('').trigger('change');
    //  $("#chk-purchaseitem").prop( "checked", true);
     $('#tns-remarks').val(''); 

     $('#trans_type').val('New');
   });

   // Product Details
   $(".goods-form").submit(function (e) {
      e.preventDefault();

      if ($('#trans_type').val() == 'New'){
          var title = "Do you want to save new product details?";
      }else{
          var title = "Do you want to update product details?";
      }

      swal.fire({
          title: title,
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

            var itemid = $("#txt-itemid").val();
            var pdesc = $("#tns-pdesc").val();
            var categorycode = $("#sel-categorycode").val();
            // var brandcode = $("#sel-brandcode").val();

            if ($('#chk-isactive').prop('checked')){
              var isactive = "1";
            }else{
              var isactive = "0";
            }

            var meas1 = $("#sel-meas1").val();

            var txt_eqnum = $("#num-eqnum").val();
            var eqnum = parseFloat(txt_eqnum.replaceAll(",",""));

            var meas2 = $("#sel-meas2").val();
            var itemcode = $("#tns-itemcode").val();

            var txt_ucost = $("#num-ucost").val();
            var ucost = parseFloat(txt_ucost.replaceAll(",","")); 

            var txt_reorder = $("#num-reorder").val();
            var reorder = parseFloat(txt_reorder.replaceAll(",",""));  

            var txt_pweight = $("#num-pweight").val();
            var pweight = parseFloat(txt_pweight.replaceAll(",",""));
            
            var wmeas = $("#sel-wmeas").val();

            // if ($('#chk-purchaseitem').prop('checked')){
            //   var purchaseitem = "1";
            // }else{
            //   var purchaseitem = "0";
            // }
            var remarks = $("#tns-remarks").val();  

            // Send data to Ajax
            var items = new FormData();
            items.append("trans_type", trans_type);

            items.append("itemid", itemid);
            items.append("pdesc", pdesc);
            items.append("categorycode", categorycode);
            // items.append("brandcode", brandcode);
            items.append("isactive", isactive);
            items.append("meas1", meas1);
            items.append("eqnum", eqnum);
            items.append("meas2", meas2);
            items.append("itemcode", itemcode); 
            items.append("ucost", ucost); 
            items.append("reorder", reorder);  
            items.append("pweight", pweight); 
            items.append("wmeas", wmeas);
            // items.append("purchaseitem", purchaseitem);
            items.append("remarks", remarks);          
            $.ajax({
               url:"ajax/goods_save_record.ajax.php",
               method: "POST",
               data: items,
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

   $("#btn-search").click(function(){
      $(".goodsTable").DataTable().clear();
      fg.draw();
      $('#lst-categorycode').val('').trigger('change');
   }); 

   $("#lbl-lst-categorycode").click(function(){
     $("#lst-categorycode").val('').trigger('change');
   });
   
   // Search Item - Modal Form dynamic selector
   $('#lst-categorycode').on("change", function(){
      let categorycode = $("#lst-categorycode").val();
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

      $.ajax({
           url:"ajax/goods_list.ajax.php",   
           method: "POST",                
           data: data,                    
           cache: false,                  
           contentType: false,            
           processData: false,            
           dataType:"json",               
           success:function(answer){
                $(".goodsTable").DataTable().clear();
                for(var i = 0; i < answer.length; i++) {
                  percent = Math.round(i/answer.length*100);
                  var options = {
                    text: percent + "% complete."
                  };
                  let item = answer[i];
                  let itemid = item.itemid;
                  let itemcode = item.itemcode;
                  let catdescription = item.catdescription;
                  let pdesc = item.pdesc;
                  let ucost = numberWithCommas(item.ucost);                  

                  var button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnEditProduct' itemid='"+itemid+"'><i class='icon-check'></i></button></td>";  
                  fg.row.add([catdescription, itemcode, pdesc, ucost, button]); 
                }
                fg.draw();

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

   $('#modal-search-goods').on('shown.bs.modal', function () {
        $('#lst-categorycode').val('').trigger('change');

        // Clear DataTable filter (search box)
        if ($.fn.DataTable.isDataTable('.goodsTable')) {
            $('.goodsTable').DataTable().search('').draw();
        }
   });

   $(".goodsTable tbody").on('click', '.btnEditProduct', function () {
      var itemid = $(this).attr("itemid");
      var data = new FormData();
      data.append("itemid", itemid);
      $.ajax({
       url:"ajax/goods_get_record.ajax.php",
         method: "POST",
         data: data,
         cache: false,
         contentType: false,
         processData: false,
         dataType:"json",
         success:function(answer){
            $("#txt-itemid").val(answer["itemid"]);
            $("#tns-pdesc").val(answer["pdesc"]);
            $("#sel-categorycode").val(answer["categorycode"]).trigger('change');

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
            $("#tns-itemcode").val(answer["itemcode"]);
            $("#num-ucost").val(numberWithCommas(answer["ucost"]));
            $("#num-reorder").val(numberWithCommas(answer["reorder"]));
            $("#num-pweight").val(numberWithCommas(answer["pweight"]));
            $("#sel-wmeas").val(answer["wmeas"]).trigger('change');

            // if (answer["purchaseitem"] == '1'){
            //    $("#chk-purchaseitem").prop( "checked", true);
            //    $("#chk-purchaseitem").val('1');
            // }else{
            //    $("#chk-purchaseitem").prop( "checked", false);
            //    $("#chk-purchaseitem").val('0');
            // }  

            $("#tns-remarks").val(answer["remarks"]);          

            $("#trans_type").val("Update");
            $("#modal-search-goods").modal('hide');
        }
      })
   });        
});   