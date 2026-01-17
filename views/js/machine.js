if (!$.fn.DataTable.isDataTable('.machineListTable')) {
  var pt = $('.machineListTable').DataTable({
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
   $('#sel-machstatus').val('Operational').trigger('change');
   
   // Disable "Under Repair" and "Under Maintenance"
   $('#sel-machstatus option[value="Under Repair"]').prop('disabled', true);
   $('#sel-machstatus option[value="Under Maintenance"]').prop('disabled', true);

   $("#lbl-lst-classcode").click(function(){
      $("#lst-classcode").val('').trigger('change');
   });  

   $("#lbl-lst-buildingcode").click(function(){
      $("#lst-buildingcode").val('').trigger('change');
   });    

   $("#lbl-lst-isactive").click(function(){
      $("#lst-isactive").val('').trigger('change');
   }); 

   $("#lbl-lst-categorycode").click(function(){
      $("#sel-categorycode").val('').trigger('change');
   }); 

   // When browsing Machine - set Condition to Operational
   // This will prevent AJAX from running twice
   $("#btn-search").click(function(){
      $(".machineListTable").DataTable().clear();
      pt.draw();
      $('#lst-machstatus').val('').trigger('change');
   });

   $('#modal-search-machine').on('shown.bs.modal', function (e) {
     $('div.dataTables_filter input').focus();
     $('div.dataTables_filter input').val('');
     $('.machineListTable').DataTable().search("").draw();
   });
   
   // Machine List - Modal Form dynamic selector
   $('#lst-classcode, #lst-buildingcode, #lst-isactive, #lst-machstatus').on("change", function(){
      let classcode = $("#lst-classcode").val();
      let buildingcode = $("#lst-buildingcode").val();
      let isactive = $("#lst-isactive").val();
      let machstatus = $("#lst-machstatus").val();

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
      data.append("buildingcode", buildingcode);
      data.append("isactive", isactive);
      data.append("machstatus", machstatus);

      $.ajax({
          url:"ajax/machine_list.ajax.php",   
          method: "POST",                
          data: data,                    
          cache: false,                  
          contentType: false,            
          processData: false,            
          dataType:"json",               
          success:function(answer){
                $(".machineListTable").DataTable().clear();
                for(var i = 0; i < answer.length; i++) {
                  percent = Math.round(i/answer.length*100);
                  var options = {
                    text: percent + "% complete."
                  };

                  let ml = answer[i];
                  let machineid = ml.machineid;
                  let machinedesc = ml.machinedesc;
                  let classname = ml.classname;
                  let buildingname = ml.buildingname;
                  let isactive = ml.isactive;
                  let machstatus = ml.machstatus;

                  if (isactive == 0){
                    var status = 'Inactive';
                  }else{
                    var status = 'Active';
                  }  

                  var button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnEditMachine' machineid='"+machineid+"'><i class='icon-pencil3'></i></button></td>";  
                  pt.row.add([machinedesc, classname, buildingname, status, machstatus, button]); 
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

   $(".machineListTable tbody").on('click', '.btnEditMachine', function () {
    var machineid = $(this).attr("machineid");

    var data = new FormData();
    data.append("machineid", machineid);
    $.ajax({
      url:"ajax/machine_get_record.ajax.php",
      method: "POST",
      data: data,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(answer){
          $("#sel-machstatus").prop("disabled", true); 

          $("#sel-classcode").val(answer["classcode"]).trigger('change');
          $("#sel-machtype").val(answer["machtype"]).trigger('change');
          $("#sel-buildingcode").val(answer["buildingcode"]).trigger('change');

          if (answer["isactive"] == '1'){
            $("#num-isactive").prop( "checked", true);
            $("#num-isactive").val('1');
          }else{
            $("#num-isactive").prop( "checked", false);
            $("#num-isactive").val('0');
          }

          $("#txt-machineid").val(answer["machineid"]);
          $("#sel-categorycode").val(answer["categorycode"]).trigger('change');
          $("#sel-machstatus").val(answer["machstatus"]).trigger('change');
          $("#txt-machabbr").val(answer["machabbr"]);
          $("#txt-machinedesc").val(answer["machinedesc"]);
          $("#tns-image").val("");
          $("#attributeList").val(answer["attributelist"]);

          $("#btn-save").prop('disabled', false);
          $(".machineAttributes").empty();

          var data_items = new FormData();
          data_items.append("machineid", machineid);
          $.ajax({
            url:"ajax/machine_get_attributes.ajax.php",
            method: "POST",
            data: data_items,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(machines){
              for(var a = 0; a < machines.length; a++) {
                var machine = machines[a];
                var machineid = machine.machineid;
                var attribute = machine.attribute;
                var detail = machine.detail;

                $(".machineAttributes").append(
                  '<tr>'+   
                  '<td width="30%" style="padding:2px;">'+   
                     '<div class="input-group">'+
                        '<span style="padding:2px;" class="input-group-prepend"><button type="button" style="color:coral;" class="btn btn-sm btn-light removeAttribute"><i class="icon-undo"></i></button></span>'+         
                        '<input type="text" style="padding:2px;" class="form-control attribName" machineid="'+machineid+'" value="'+attribute+'" required>'+
                      '</div>'+
                  '</td>'+            
        
                  '<td width="70%" style="padding:2px;">'+
                     '<input type="text" style="padding:2px;padding-right:17px;" class="form-control attribDesc" machineid="'+machineid+'" value="'+detail+'" required>'+
                  '</td>' +                                 
                '</tr>') 
                listAttributes();   
                $('.attribName').focus();
              }
            }
          });
          $("#trans_type").val("Update");
          $("#modal-search-machine").modal('hide');
      }
    });
   });  


   $("#btn-new").click(function(){
      // let image = $('#tns-image')[0].files[0];
      // let image_size = $('#tns-image')[0].files[0].size;
      // let image_name = $('#tns-image')[0].files[0].name;
      // let image_type = $('#tns-image')[0].files[0].type;

      // alert(image_name);
   	  new_machine();
   });

   $("#btn-attributes").click(function(){
       $(".machineAttributes").append(
          '<tr>'+   
          '<td width="30%" style="padding:2px;">'+   
             '<div class="input-group">'+
                '<span style="padding:2px;" class="input-group-prepend"><button type="button" style="color:coral;" class="btn btn-sm btn-light removeAttribute"><i class="icon-undo"></i></button></span>'+         
                '<input type="text" style="padding:2px;" class="form-control attribName" value="" required>'+
              '</div>'+
          '</td>'+            

          '<td width="70%" style="padding:2px;">'+
             '<input type="text" style="padding:2px;padding-right:17px;" class="form-control attribDesc" value="" required>'+
          '</td>' +                                 
        '</tr>') 
        listAttributes();   
        $('.attribName').focus();  
   });

   $("#machine-form").on("change keypress keyup blur", "input.attribName,input.attribDesc", function(){
      listAttributes();
   })

   $("#machine-form").on("click", "button.removeAttribute", function(){
      $(this).parent().parent().parent().parent().remove();
      listAttributes();
   })   

   function listAttributes(){
     var attributeList = [];
     var attribName = $(".attribName");
     var attribDesc = $(".attribDesc");

     if (attribName.length > 0){
       for(var i = 0; i < attribName.length; i++){
         attributeList.push({"attribute" : $(attribName[i]).val(),
                             "detail" : $(attribDesc[i]).val()})
       }
       $("#attributelist").val(JSON.stringify(attributeList));
     }else{
       $("#attributelist").val("");
     }
     $("#btn-save").prop('disabled', false);
   }      

   $("#machine-form").submit(function (e) {
      e.preventDefault();
      save_machine();
   });  

   $("#tns-image").change(function(){
      var newImage = this.files[0]; 
      if (newImage["type"] != "image/jpeg" && newImage["type"] != "image/png"){
        $("#tns-image").val("");
        swal.fire({
          type: "error",
          title: "Error uploading image",
          text: "Image has to be JPEG or PNG!",
          confirmButtonText: "Close",
          confirmButtonClass: 'btn btn-outline-success',
          buttonsStyling: false
        });
      }else if(newImage["size"] > 2000000){
        $("#tns-image").val("");
        swal.fire({
          type: "error",
          title: "Error uploading image",
          text: "Image too big. It has to be less than 2Mb!",
          confirmButtonText: "Close",
          confirmButtonClass: 'btn btn-outline-success',
          buttonsStyling: false
        });
      }else{
        var imgData = new FileReader;
        imgData.readAsDataURL(newImage);
        $(imgData).on("load", function(event){
          var routeImg = event.target.result;
          $(".preview").attr("src", routeImg);
        });
      }
   })     

   function new_machine(){
      swal.fire({
          title: 'Do you want to add new machine?',
          type: 'question',
          showCancelButton: true,
          confirmButtonText: 'Yes, Add it!',
          cancelButtonText: 'No',
          confirmButtonClass: 'btn btn-outline-success',
          cancelButtonClass: 'btn btn-outline-danger',
          allowOutsideClick: false,
          buttonsStyling: false
      }).then(function(result) {
          if(result.value) {  
          	initialize();
          }
      }); 	
   }   

   function initialize(){
     $("#sel-classcode").val('').trigger('change');
     $("#sel-machtype").val('').trigger('change');
     $('#txt-machabbr').val('');
     $('#txt-machinedesc').val('');
     $("#sel-categorycode").val('').trigger('change');
     $("#sel-buildingcode").val('').trigger('change');
     $("#chk-isactive").prop( "checked", true); 
     $('#txt-machineid').val('');
     $("#sel-machstatus").val('').trigger('change');
     $('#attributelist').val('');
     $(".machineAttributes").empty();

     $("#sel-machstatus").prop("disabled", false);

     // Disable "Under Repair" and "Under Maintenance"
     $('#sel-machstatus option[value="Under Repair"]').prop('disabled', true);
     $('#sel-machstatus option[value="Under Maintenance"]').prop('disabled', true);

     // Enable "Operational" and "Standby"
     $('#sel-machstatus option[value="Operational"]').prop('disabled', false);
     $('#sel-machstatus option[value="Standby"]').prop('disabled', false);

     // Reset dropdown to blank or Operational
     $('#sel-machstatus').val('Operational').trigger('change');     

     $('#trans_type').val('New');
   }

   function save_machine(){
     swal.fire({
         title: 'Do you want to save machine details?',
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

            let classcode = $("#sel-classcode").val();
            let machtype = $("#sel-machtype").val();
            let machabbr = $('#txt-machabbr').val();
            let machinedesc = $('#txt-machinedesc').val();
            let categorycode = $("#sel-categorycode").val();
            let machineid = $('#txt-machineid').val();
            let buildingcode = $("#sel-buildingcode").val();
            let machstatus = $("#sel-machstatus").val();
            let attributelist = $("#attributelist").val();
            let image = $('#tns-image')[0].files[0];

            if ($('#num-isactive').prop('checked')){
              var isactive = "1";
            }else{
              var isactive = "0";
            }

            var machine = new FormData();
    		    machine.append("trans_type", trans_type);
    		    machine.append("classcode", classcode);
    		    machine.append("machtype", machtype);
    		    machine.append("machabbr", machabbr);
            machine.append("categorycode", categorycode);
    		    machine.append("machinedesc", machinedesc);
            machine.append("machineid", machineid);
    		    machine.append("buildingcode", buildingcode);
            machine.append("isactive", isactive);
    		    machine.append("machstatus", machstatus);
            machine.append("image", image);
            machine.append("attributelist", attributelist);

    		    $.ajax({
    		        url:"ajax/machine_save_record.ajax.php",
    		        method: "POST",
    		        data: machine,
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
    		              title: 'Machine details successfully saved!',
    		              type: 'success',
    		              allowOutsideClick: false,
    		              showConfirmButton: false,
    		              timer: 1500
    		           })
    		           initialize(); 
    		        }
		        }) // ajax
         } // if
     });   	
   }
});	