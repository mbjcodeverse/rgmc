

$(function() {
   //$('#tns-tin').mask('000-000-000-000');

   // Adjust column automatically when modal form is fired
   $('#modal-search-supplier').on('shown.bs.modal', function () {
     var tableSupplier = $('.supplierTable').DataTable();
     tableSupplier.columns.adjust();
   }); 

   $(".select").select2({
      minimumResultsForSearch: Infinity,
   });

   $(".select-search").select2();

   $('#form-supplier input[id^="num"]').on("keypress", function (e) {
      return _helper.isNumericDash(e) ? true : e.preventDefault();
   });

   $('#form-supplier input[id^="txt"]').on("keypress", function (e) {
      return _helper.isString(e) ? true : e.preventDefault();
   });

   $('#form-supplier input[id^="tns"]').on("keypress", function (e) {
      return _helper.allChars(e) ? true : e.preventDefault();
   });   

   $("#sel-country").val("PH").trigger('change');
   $('#tns-name').focus();

   // $("div.dataTables_filter input").focus();

   $("#btn-new").click(function(e){
     e.preventDefault();
     $('#txt-suppliercode').val('');
     $('#tns-name').val('');
     $("#sel-vatdesc").val('').trigger('change');
     $('#tns-tin').val('');
     $('#tns-description').val('');
     $('#tns-mobile').val('');
     $('#tns-landline').val(''); 
     $('#tns-faxnum').val('');  
     $('#tns-website').val(''); 
     $('#tns-contactperson').val('');
     $("#sel-country").val("PH").trigger('change');
     $("#chk-isactive").prop( "checked", true); 
     $('#tns-address').val(''); 

     $('#trans_type').val('New');
     $('#num-id').val('');
     $('#tns-name').focus();
   }); 

   $("#btn-search").click(function(){
     $("#modal-search-supplier").modal('show');
   });      

   $('.supplierTable tbody').on('dblclick', 'tr', function () {
    var idSupplier = $(this).attr("idSupplier");
    var data = new FormData();
      data.append("idSupplier", idSupplier);
      $.ajax({
       url:"ajax/get_supplier_record.ajax.php",
         method: "POST",
         data: data,
         cache: false,
         contentType: false,
         processData: false,
         dataType:"json",
         success:function(answer){
          $("#num-id").val(answer["id"]);
          $("#txt-suppliercode").val(answer["suppliercode"]);

          if (answer["isactive"] == '1'){
            $("#chk-isactive").prop( "checked", true);
            $("#chk-isactive").val('1');
          }else{
            $("#chk-isactive").prop( "checked", false);
            $("#chk-isactive").val('0');
          }
          
          $("#tns-name").val(answer["name"]);
          $("#tns-tin").val(answer["tin"]);
          $("#sel-vatdesc").val(answer["vatdesc"]).trigger('change');
          $("#tns-description").val(answer["description"]);
          $("#tns-mobile").val(answer["mobile"]);
          $("#tns-landline").val(answer["landline"]);
          $("#tns-faxnum").val(answer["faxnum"]);
          $("#tns-website").val(answer["website"]);
          $("#tns-contactperson").val(answer["contactperson"]);
          $("#sel-country").val(answer["country"]).trigger('change');
          $("#tns-address").val(answer["address"]);

          $("#trans_type").val("Update");
          $("#modal-search-supplier").modal('hide');
        }
      })
   }); 
});
