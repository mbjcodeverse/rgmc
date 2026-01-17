$(function() {
   $('#tns-buildingname').focus();

   $('#form-building input[id^="tns"]').on("keypress", function (e) {
      return _helper.allChars(e) ? true : e.preventDefault();
   });  

   $("#btn-new").click(function(){
     $('#tns-buildingname').val(''); 
     $('#num-buildingcode').val('');

     $('#trans_type').val('New');
     $('#num-id').val('');
     $('#tns-buildingname').focus();
   });

   $('.buildingTable tbody').on('dblclick', 'tr', function () {
    var idBuilding = $(this).attr("idBuilding");
    var data = new FormData();
      data.append("idBuilding", idBuilding);
      $.ajax({
       url:"ajax/building_get_record.ajax.php",
         method: "POST",
         data: data,
         cache: false,
         contentType: false,
         processData: false,
         dataType:"json",
         success:function(answer){
          $("#num-id").val(answer["id"]);
          $("#tns-buildingname").val(answer["buildingname"]);
          $("#num-buildingcode").val(answer["buildingcode"]);

          $("#trans_type").val("Update");
          $("#modal-search-building").modal('hide');
        }
      })
   }); 
});
