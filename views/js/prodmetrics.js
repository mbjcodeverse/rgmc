if (!$.fn.DataTable.isDataTable('.productmetricsTransactionTable')) {
  var pt = $('.productmetricsTransactionTable').DataTable({
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
  $('#date-mdate').daterangepicker({
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
    $(".productmetricsTransactionTable").DataTable().clear();
    pt.draw();
    $('#lst-mstatus').val('Posted').trigger('change');
  });   

  $("#lbl-lst-mstatus").click(function(){
      $("#lst-mstatus").val('').trigger('change');
  });   
  
  $("#lbl-lst-empid").click(function(){
      $("#lst-empid").val('').trigger('change');
  });  

  $("#btn-new").click(function(){
      initialize();
  });

  $('#lst_date_range, #lst-empid, #lst-mstatus').on("change", function(){
    let date_range = $("#lst_date_range").val();
    let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
    let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);

    let empid = $("#lst-empid").val();
    let mstatus = $("#lst-mstatus").val();

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
    data.append("mstatus", mstatus);

    $.ajax({
        url:"ajax/productmetrics_transaction_list.ajax.php",   
        method: "POST",                
        data: data,                    
        cache: false,                  
        contentType: false,            
        processData: false,            
        dataType:"json",               
        success:function(answer){
              $(".productmetricsTransactionTable").DataTable().clear();
              for(var i = 0; i < answer.length; i++) {
                percent = Math.round(i/answer.length*100);
                var options = {
                  text: percent + "% complete."
                };

                let pm = answer[i];
                let pm_date = pm.mdate;
                let mdate = pm_date.split("-");
                mdate = mdate[1] + "/" + mdate[2] + "/" + mdate[0];
                let prodmetid = pm.prodmetid;
                let catdescription = pm.catdescription;
                let headcount = numberWithCommas(pm.headcount);
                let dailyrate = numberWithCommas(pm.dailyrate);
                let amount = numberWithCommas(pm.amount);

                var button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnEditProductmetrics' prodmetid='"+prodmetid+"'><i class='icon-pencil3'></i></button></td>";  
                pt.row.add([mdate, catdescription, prodmetid, headcount, dailyrate, amount, button]); 
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
  $(".productmetricsTransactionTable tbody").on('click', '.btnEditProductmetrics', function () {
    var prodmetid = $(this).attr("prodmetid");
    var data = new FormData();
    data.append("prodmetid", prodmetid);
    $.ajax({
      url:"ajax/productmetrics_get_record.ajax.php",
      method: "POST",
      data: data,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(answer){
          let mdate = answer["mdate"].split("-");
          mdate = mdate[1] + "/" + mdate[2] + "/" + mdate[0];
          if (mdate == '00/00/0000'){      
            mdate = '';
          }
          $("#date-mdate").val(mdate);
          $("#txt-prodmetid").val(answer["prodmetid"]);
          $("#sel-categorycode").val(answer["categorycode"]).trigger('change');
          $("#num-headcount").val(numberWithCommas(answer["headcount"]));
          $("#num-dailyrate").val(numberWithCommas(answer["dailyrate"]));
          $("#num-amount").val(numberWithCommas(answer["amount"]));
          
          let mstatus = $(answer["mstatus"]).val();
          switch (mstatus){
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
          $("#modal-search-productmetrics").modal('hide');
      }
    });
  });

  function initialize(){
    $("#date-mdate").val("");
    $("#sel-categorycode").val('').trigger('change');
    $("#txt-prodmetid").val("");
    $("#num-headcount").val("0.00");
    $("#num-dailyrate").val("0.00");
    $("#num-amount").val("0.00");

    $("#btn-save").prop('disabled', false);
    $("#btn-cancel").prop('disabled', true);

    $("#trans_type").val("New");
  }

   $(".productmetrics-form").submit(function (e) {
      e.preventDefault();
      swal.fire({
          title: 'Do you want to save production metrics?',
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
            let categorycode = $("#sel-categorycode").val();
            let prodmetid = $("#txt-prodmetid").val();

            let txt_headcount = $("#num-headcount").val();
            let txt_dailyrate = $("#num-dailyrate").val();
            let txt_amount = $("#num-amount").val();
            var headcount = parseFloat(txt_headcount.replaceAll(",",""));
            var dailyrate= parseFloat(txt_dailyrate.replaceAll(",",""));
            var amount= parseFloat(txt_amount.replaceAll(",",""));

            let format_mdate = $("#date-mdate").val().split("/");
            format_mdate = format_mdate[2] + "-" + format_mdate[0] + "-" + format_mdate[1];
            let mdate = format_mdate;

            let postedby = $("#tns-postedby").val();          

            var p_metrics = new FormData();
            p_metrics.append("trans_type", trans_type);
            p_metrics.append("categorycode", categorycode);
            p_metrics.append("prodmetid", prodmetid);
            p_metrics.append("headcount", headcount);
            p_metrics.append("dailyrate", dailyrate);
            p_metrics.append("amount", amount);
            p_metrics.append("mdate", mdate);
            p_metrics.append("postedby", postedby);
           
            $.ajax({
               url:"ajax/productmetrics_save_record.ajax.php",
               method: "POST",
               data: p_metrics,
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
                    title: 'Production metrics successfully saved!',
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
            var prodmetid = $("#txt-prodmetid").val();            
            var cancelproductmetrics = new FormData();
            cancelproductmetrics.append("prodmetid", prodmetid);            
            $.ajax({
               url:"ajax/productmetrics_cancel_record.ajax.php",
               method: "POST",
               data: cancelproductmetrics,
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
   
   $('#num-headcount, #num-dailyrate').on("change keyup", function(){
      let num_headcount = $('#num-headcount').val();
      let num_dailyrate = $('#num-dailyrate').val();
      let headcount = parseFloat(num_headcount.replace(",",""));
      let dailyrate = parseFloat(num_dailyrate.replace(",",""));
      let amount = dailyrate * headcount;
      $('#num-amount').val(numberWithCommas(amount.toFixed(2)));
   });  
});    