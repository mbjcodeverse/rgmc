$(function() {
   $('#tns-classname').focus();

   $('#form-class input[id^="tns"]').on("keypress", function (e) {
      return _helper.allChars(e) ? true : e.preventDefault();
   });  

   $("#btn-new").click(function(){
     $('#tns-classname').val(''); 
     $('#num-classcode').val('');

     $('#trans_type').val('New');
     $('#num-id').val('');
     $('#tns-classname').focus();
   });

   $('.classTable tbody').on('dblclick', 'tr', function () {
    var idClass = $(this).attr("idClass");
    var data = new FormData();
      data.append("idClass", idClass);
      $.ajax({
         url:"ajax/classification_get_record.ajax.php",
         method: "POST",
         data: data,
         cache: false,
         contentType: false,
         processData: false,
         dataType:"json",
         success:function(answer){
            $("#num-id").val(answer["id"]);
            $("#tns-classname").val(answer["classname"]);
            $("#num-classcode").val(answer["classcode"]);

            $("#trans_type").val("Update");
            $("#modal-search-class").modal('hide');
        }
      })
   }); 
});
