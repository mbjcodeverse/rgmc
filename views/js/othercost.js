if (!$.fn.DataTable.isDataTable('.othercostTransactionTable')) {
  var pt = $('.othercostTransactionTable').DataTable({
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
  $('#date-odate').daterangepicker({
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
    $(".othercostTransactionTable").DataTable().clear();
    pt.draw();
    $('#lst-ostatus').val('Posted').trigger('change');
  });   

  $("#lbl-lst-ostatus").click(function(){
      $("#lst-ostatus").val('').trigger('change');
  });   
  
  $("#lbl-lst-empid").click(function(){
      $("#lst-empid").val('').trigger('change');
  });  

  $("#btn-new").click(function(){
      initialize();
  });

  $('#lst_date_range, #lst-empid, #lst-ostatus').on("change", function(){
    let date_range = $("#lst_date_range").val();
    let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
    let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);

    let empid = $("#lst-empid").val();
    let ostatus = $("#lst-ostatus").val();

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
    data.append("start_date", start_date);
    data.append("end_date", end_date);
    data.append("empid", empid);
    data.append("ostatus", ostatus);

    $.ajax({
        url:"ajax/othercost_transaction_list.ajax.php",   
        method: "POST",                
        data: data,                    
        cache: false,                  
        contentType: false,            
        processData: false,            
        dataType:"json",               
        success:function(answer){
              $(".othercostTransactionTable").DataTable().clear();
              for(var i = 0; i < answer.length; i++) {
                percent = Math.round(i/answer.length*100);
                var options = {
                  text: percent + "% complete."
                };

                let oc = answer[i];
                let oc_date = oc.odate;
                let odate = oc_date.split("-");
                odate = odate[1] + "/" + odate[2] + "/" + odate[0];
                let ocostid = oc.ocostid;
                let electricity = numberWithCommas(oc.electricity);
                let manpower = numberWithCommas(oc.manpower);
                let sales = numberWithCommas(oc.sales);

                var button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnEditOthercost' ocostid='"+ocostid+"'><i class='icon-pencil3'></i></button></td>";  
                pt.row.add([odate, ocostid, sales, electricity, manpower, button]); 
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

  // Get Record
  $(".othercostTransactionTable tbody").on('click', '.btnEditOthercost', function () {
    var ocostid = $(this).attr("ocostid");
    var data = new FormData();
    data.append("ocostid", ocostid);
    $.ajax({
      url:"ajax/othercost_get_record.ajax.php",
      method: "POST",
      data: data,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(answer){
          let odate = answer["odate"].split("-");
          odate = odate[1] + "/" + odate[2] + "/" + odate[0];
          if (odate == '00/00/0000'){      
            odate = '';
          }
          $("#date-odate").val(odate);
          $("#txt-ocostid").val(answer["ocostid"]);
          $("#num-electricity").val(numberWithCommas(answer["electricity"]));
          $("#num-manpower").val(numberWithCommas(answer["manpower"]));
          $("#num-sales").val(numberWithCommas(answer["sales"]));
          
          let ostatus = $(answer["ostatus"]).val();
          switch (ostatus){
            case 'Cancelled':
              $("#btn-save").prop('disabled', true);
            //   $("#btn-cancel").prop('disabled', true);
              break; 
            case 'Posted':
              $("#btn-save").prop('disabled', false);
            //   $("#btn-cancel").prop('disabled', false);
              break;                                          
          }          

          $("#trans_type").val("Update"); 
          $("#modal-search-othercost").modal('hide');
      }
    });
  });

  function initialize(){
    $("#date-odate").val("");
    $("#txt-ocostid").val("");
    $("#num-electricity").val("0.00");
    $("#num-manpower").val("0.00");
    $("#num-sales").val("0.00");

    $("#btn-save").prop('disabled', false);
    $("#btn-cancel").prop('disabled', true);

    $("#trans_type").val("New");
  }

   $(".othercost-form").submit(function (e) {
      e.preventDefault();
      swal.fire({
          title: 'Do you want to save other cost?',
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
            let ocostid = $("#txt-ocostid").val();

            let txt_electricity = $("#num-electricity").val();
            let txt_manpower = $("#num-manpower").val();
            let txt_sales = $("#num-sales").val();
            var electricity = parseFloat(txt_electricity.replaceAll(",",""));
            var manpower= parseFloat(txt_manpower.replaceAll(",",""));
            var sales= parseFloat(txt_sales.replaceAll(",",""));

            let format_odate = $("#date-odate").val().split("/");
            format_odate = format_odate[2] + "-" + format_odate[0] + "-" + format_odate[1];
            let odate = format_odate;

            let postedby = $("#tns-postedby").val();          

            var other_cost = new FormData();
            other_cost.append("trans_type", trans_type);
            other_cost.append("ocostid", ocostid);
            other_cost.append("electricity", electricity);
            other_cost.append("manpower", manpower);
            other_cost.append("sales", sales);
            other_cost.append("odate", odate);
            other_cost.append("postedby", postedby);
           
            $.ajax({
               url:"ajax/othercost_save_record.ajax.php",
               method: "POST",
               data: other_cost,
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
                    title: 'Other cost successfully saved!',
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
            var ocostid = $("#txt-ocostid").val();            
            var cancelothercost = new FormData();
            cancelothercost.append("ocostid", ocostid);            
            $.ajax({
               url:"ajax/othercost_cancel_record.ajax.php",
               method: "POST",
               data: cancelothercost,
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