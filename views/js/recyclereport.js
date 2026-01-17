$(function() {
     let user_level = $('#user_level').val();
     let prod_opr = $('#prod_opr').val();
     let current_user = $('#txt-generatedby').val();
     if (user_level == 'Operator'){
        if (prod_opr == 'Restricted'){
          $("#lst-postedby").val(current_user).trigger('change');
          $("#lst-postedby").prop('disabled', true);
          // $("#lbl-lst-postedby").off('click');
          $("#lbl-lst-postedby")
          .css({
              'pointer-events': 'none',
              'opacity': '0.5',
              'cursor': 'not-allowed'
          });
        }
        $('#btn-return').show();
     }

     $("#btn-return").click(function(){
        window.location = 'prodoperator';
     });

     $('#lst_date_range').daterangepicker({
        ranges:{
          'All'           : [moment('2025-06-30'), moment()],
          'Today'         : [moment(),moment()],
          'Yesterday'     : [moment().subtract(1,'days'), moment().subtract(1,'days')],
          'Last 7 Days'   : [moment().subtract(6,'days'), moment()],
          'Last 30 Days'  : [moment().subtract(30,'days'), moment()],
          'This Month'    : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'    : [moment().subtract(1, 'months').startOf('month'), moment().subtract(1, 'months').endOf('month')]
        },
        startDate: moment().startOf('month'), 
        endDate: moment().endOf('month'),
        minDate: moment('2025-06-30')
     });
  
     $("#lst-recstatus").val('Posted').trigger('change');  
  
     $("#lbl-lst-machineid").click(function(){
        $("#lst-machineid").val('').trigger('change');
     }); 
  
     $("#lbl-lst-categorycode").click(function(){
        $("#lst-categorycode").val('').trigger('change');
     });     
  
     $("#lbl-lst-postedby").click(function(){
        $("#lst-postedby").val('').trigger('change');
     });

     $("#lbl-lst-recycleby").click(function(){
        $("#lst-recycleby").val('').trigger('change');
     });     
  
     $("#lbl-lst-recstatus").click(function(){
        $("#lst-recstatus").val('').trigger('change');
     });
  
     $('#lst-machineid, #lst_date_range, #lst-categorycode, #lst-postedby, #lst-recycleby, #lst-recstatus, #lst-reptype').on("change", function(){
        $("#btn-print-report").prop('disabled', false);
        $("#btn-export").prop('disabled', false);
  
        let machineid = $('#lst-machineid').val();
        let date_range = $("#lst_date_range").val();
        let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
        let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
  
        let postedby = $("#lst-postedby").val();
        let recycleby = $("#lst-recycleby").val();
        let categorycode = $("#lst-categorycode").val();
        let recstatus = $("#lst-recstatus").val();
        let reptype = $("#lst-reptype").val();
  
        var data = new FormData();
        data.append("machineid", machineid);
        data.append("start_date", start_date);
        data.append("end_date", end_date);
        data.append("categorycode", categorycode);
        data.append("postedby", postedby);
        data.append("recycleby", recycleby);
        data.append("recstatus", recstatus);
        data.append("reptype", reptype);
  
        $.ajax({
             url:"ajax/recycle_report.ajax.php",   
             method: "POST",                
             data: data,                    
             cache: false,                  
             contentType: false,            
             processData: false,            
             dataType:"json",               
             success:function(answer){
                $(".release_content").empty();
                var html = [];
                // Overall Incoming Category
                if (reptype == 1){
                      html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                        html.push('<table class="table mx-auto w-auto">');
                            html.push('<thead>');
                              html.push('<tr>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Category</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Qty</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                              html.push('</tr>');
                            html.push('</thead>');
  
                              for(var i = 0; i < answer.length; i++) {
                                  var release = answer[i];
                                  var catdescription = release.catdescription;
                                  var total_qty = numberWithCommas(release.total_qty);
                                  var total_amount = numberWithCommas(release.total_amount);
                                      html.push('<tr>');
  
                                        if (i == answer.length - 1){
                                          html.push('<td style="font-size:1.1em;font-weight:bold;border-top: 2px solid white;">OVERALL AMOUNT</td>');
                                          html.push('<td style="font-size:1.1em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+total_qty+'</td>');
                                          html.push('<td style="font-size:1.1em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+total_amount+'</td>');
                                        }else{
                                          html.push('<td>'+catdescription+'</td>');
                                          html.push('<td style="text-align:right;">'+total_qty+'</td>');
                                          html.push('<td style="text-align:right;">'+total_amount+'</td>');
                                        }
                                      html.push('</tr>');
                              }   
                          html.push('</table>');
                      html.push('</div>');
                }else if (reptype == 2){
                      html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                        html.push('<table class="table mx-auto w-auto">');
                            html.push('<thead>');
                              html.push('<tr>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Category</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Items</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Qty</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                              html.push('</tr>');
                            html.push('</thead>');
  
                            for(var i = 0; i < answer.length; i++) {
                                var release = answer[i];
                                var catdescription = release.catdescription;
                                var meas2 = release.meas2;
                                var pdesc = release.prodname + ' (' + meas2.toUpperCase() + ')';
  
                                if (release.prodname == null){
                                  pdesc = '';
                                  catdescription = '';
                                }else{
                                  if (i == 0){
                                    var prev_catdescription = release.catdescription;
                                  }else{
                                    var curr_catdescription = release.catdescription;
                                    if (prev_catdescription == curr_catdescription){
                                      catdescription = '';
                                    }
                                    var prev_catdescription = curr_catdescription;
                                  }                 
                                }
  
                                var total_qty = numberWithCommas(release.total_qty);
                                var total_amount = numberWithCommas(release.total_amount);
                                html.push('<tr>');
                                  html.push('<td>'+catdescription+'</td>');

                                  if (i == answer.length - 1){
                                    html.push('<td style="font-size:1.2em;font-weight:bold;">OVERALL AMOUNT</td>');
                                  }else{
                                    html.push('<td>'+pdesc+'</td>');
                                  }

                                  // html.push('<td>'+pdesc+'</td>');
                                  if (release.prodname == null){
                                    // html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;"></td>');
                                    html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+total_qty+'</td>');
                                    html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+total_amount+'</td>');
                                  }else{
                                    html.push('<td style="text-align:right;">'+total_qty+'</td>');
                                    html.push('<td style="text-align:right;">'+total_amount+'</td>');
                                  }  
                                html.push('</tr>');
                            }  
                                      
                        html.push('</table>');
                      html.push('</div>'); 
                }else if (reptype == 3){
                      html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                        html.push('<table class="table mx-auto w-auto">');
                          html.push('<thead>');
                          html.push('<tr>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Date</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Rec #</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Machine</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Posted by</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Recycled by</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Items</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Qty</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Cost</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                          html.push('</tr>');
                          html.push('</thead>');
  
                          for(var i = 0; i < answer.length; i++) {
                              var release = answer[i];
  
                              var rel_date = release.recdate;
                              var recdate = rel_date.substring(5, 7) + '/' + rel_date.substring(8, 10) + '/' + rel_date.substring(0, 4);
  
                              var recnumber = release.recnumber;
                              var machabbr = release.machabbr;
                              var machinedesc = release.machinedesc;
                              var recstatus = release.recstatus;
                              var name = release.name;
                              var recname = release.recname;
  
                              var prodname = release.prodname;
                              var meas2 = release.meas2;
                              var pdesc = release.prodname + ' (' + meas2.toUpperCase() + ')';
  
                              var qty = numberWithCommas(release.qty);
                              var price = numberWithCommas(release.price);
                              var tamount = numberWithCommas(release.tamount);

                              if (machabbr != ''){
                                var machine = machinedesc + ' (' + machabbr + ')';
                              }else{
                                var machine = '';
                              }
  
                              if (prodname == null){
                                recnumber = '';
                                machine = '';
                                name = '';
                                recname = '';
                                pdesc = '';
                                recdate = '';
                              }else{
                                if (i == 0){
                                  var prev_recnumber = release.recnumber;
                                  var prev_recdate = release.recdate;
                                }else{
                                  var curr_recnumber = release.recnumber;
                                  if (prev_recnumber == curr_recnumber){
                                    recnumber = '';
                                    machine = '';
                                    name = '';
                                    recname = '';
                                  }
                                  var prev_recnumber = curr_recnumber;
                                  // don't display same date
                                  var curr_recdate = release.recdate;
                                  if (prev_recdate == curr_recdate){
                                    recdate = '';
                                  }
                                  var prev_recdate = curr_recdate;                    
                                }                 
                              }
  
                              html.push('<tr>');
                                html.push('<td>'+recdate+'</td>');
  
                                if (recstatus == 'Void'){
                                  html.push('<td style="color:orange;">'+recnumber+'</td>');
                                }else{
                                  html.push('<td>'+recnumber+'</td>');
                                }

                                html.push('<td>'+machine+'</td>'); 
                                html.push('<td>'+name+'</td>'); 
                                html.push('<td>'+recname+'</td>');
  
                                if (i == answer.length - 1){
                                  html.push('<td style="font-size:1.2em;font-weight:bold;">OVERALL AMOUNT</td>');
                                }else{
                                  html.push('<td>'+pdesc+'</td>');
                                }
                                
                                if (prodname == null){
                                   html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;"></td>');
                                   html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;"></td>');
                                   html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+tamount+'</td>');
                                }else{
                                   html.push('<td style="text-align:right;">'+qty+'</td>');
                                   html.push('<td style="text-align:right;">'+price+'</td>');
                                   html.push('<td style="text-align:right;">'+tamount+'</td>');
                                }
                              html.push('</tr>');               
                          }  
                                    
                        html.push('</table>');
                      html.push('</div>');
                }else if (reptype == 4){
                      html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                        html.push('<table class="table mx-auto w-auto">');
                            html.push('<thead>');
                              html.push('<tr>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Machine</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Code</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Bldg</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Items</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Qty</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                              html.push('</tr>');
                            html.push('</thead>');
  
                              for(var i = 0; i < answer.length; i++) {
                                  var release = answer[i];
                                  var machinedesc = release.machinedesc;
                                  var machabbr = release.machabbr;
                                  var buildingname = release.buildingname;
  
                                  var machbldg = release.machinedesc + release.buildingname + release.machabbr;
  
                                  var prodname = release.prodname;
                                  var meas2 = release.meas2;
                                  var pdesc = release.prodname + ' (' + meas2.toUpperCase() + ')';
  
                                  var qty = numberWithCommas(release.qty);
                                  var tamount = numberWithCommas(release.tamount);
  
                                  if (prodname == null){
                                    machinedesc = '';
                                    machabbr = '';
                                    buildingname = '';
                                    pdesc = '';
                                  }else{
                                    if (i == 0){
                                      var prev_machbldg = machbldg;
                                    }else{
                                      var curr_machbldg = machbldg;
                                      if (prev_machbldg == curr_machbldg){
                                        machinedesc = '';
                                        machabbr = '';
                                        buildingname = '';
                                      }
                                      var prev_machbldg = curr_machbldg;                    
                                    }                 
                                  }
  
                                  html.push('<tr>');
                                    html.push('<td>'+machinedesc+'</td>');
                                    html.push('<td>'+machabbr+'</td>');
                                    html.push('<td>'+buildingname+'</td>');
  
                                    if (i == answer.length - 1){
                                      html.push('<td style="font-size:1.2em;font-weight:bold;">OVERALL AMOUNT</td>');
                                    }else{
                                      html.push('<td>'+pdesc+'</td>');
                                    }
  
                                    if (prodname == null){
                                      html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;"></td>');
                                      html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+tamount+'</td>');
                                    }else{
                                      html.push('<td style="text-align:right;">'+qty+'</td>');
                                      html.push('<td style="text-align:right;">'+tamount+'</td>');
                                    }
                                  html.push('</tr>');   
                              }   
                          html.push('</table>');
                      html.push('</div>');
                }else if (reptype == 5){
                    html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                        html.push('<table class="table mx-auto w-auto">');
                          html.push('<thead>');
                          html.push('<tr>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Machine</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Date</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Rec #</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Posted by</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Recycle by</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Items</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Qty</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Cost</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                          html.push('</tr>');
                          html.push('</thead>');
  
                          for(var i = 0; i < answer.length; i++) {
                              var release = answer[i];
  
                              var rec_date = release.recdate;
                              var recdate = rec_date.substring(5, 7) + '/' + rec_date.substring(8, 10) + '/' + rec_date.substring(0, 4);
                              
                              var recnumber = release.recnumber;
                              var machabbr = release.machabbr;
                              var machinedesc = release.machinedesc;
                              var recstatus = release.recstatus;
                              var name = release.name;
                              var reqname = release.reqname;
  
                              var prodname = release.prodname;
                              var meas2 = release.meas2;
                              var pdesc = release.prodname + ' (' + meas2.toUpperCase() + ')';
  
                              var qty = numberWithCommas(release.qty);
                              var price = numberWithCommas(release.price);
                              var tamount = numberWithCommas(release.tamount);

                              // var num_items = release.num_items;

                              if (machabbr != ''){
                                var machine = machinedesc + ' (' + machabbr + ')';
                              }else{
                                var machine = '';
                              }
  
                              if (prodname == null){
                                recnumber = '';
                                machine = '';
                                name = '';
                                reqname = '';
                                pdesc = '';
                                recdate = '';
                              }else{
                                if (i == 0){
                                  var prev_recnumber = release.recnumber;
                                  var prev_machine = release.machinedesc;
                                }else{
                                  var curr_recnumber = release.recnumber;
                                  if (prev_recnumber == curr_recnumber){
                                    recnumber = '';
                                    machine = '';
                                    name = '';
                                    reqname = '';
                                    recdate = '';
                                  }
                                  var prev_recnumber = curr_recnumber;
                                  // don't display same date
                                  var curr_machine = release.machinedesc;
                                  if (prev_machine == curr_machine){
                                    machine = '';
                                  }
                                  var prev_machine = curr_machine;                    
                                }                 
                              }
  
                              // if (prodname != null){
                              html.push('<tr>');
                                html.push('<td>'+machine+'</td>'); 
                                html.push('<td>'+recdate+'</td>');
  
                                if (recstatus == 'Void'){
                                  html.push('<td style="color:orange;">'+recnumber+'</td>');
                                }else{
                                  html.push('<td>'+recnumber+'</td>');
                                }

                                html.push('<td>'+name+'</td>'); 
                                html.push('<td>'+reqname+'</td>');
  
                                if (i == answer.length - 1){
                                  html.push('<td style="font-size:1.2em;font-weight:bold;">OVERALL AMOUNT</td>');
                                }else{
                                  html.push('<td>'+pdesc+'</td>');
                                }
                                
                                if (prodname == null){
                                    // if (num_items > 1){
                                      html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;"></td>');
                                      html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;"></td>');
                                      html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+tamount+'</td>');
                                    // }
                                }else{   
                                    html.push('<td style="text-align:right;">'+qty+'</td>');
                                    html.push('<td style="text-align:right;">'+price+'</td>');
                                    html.push('<td style="text-align:right;">'+tamount+'</td>');
                                }
                              html.push('</tr>');  

                              if (machinedesc == ''){
                                break;
                              } 
                            // }            
                          }  
                                    
                        html.push('</table>');
                      html.push('</div>');
                }
               $('.release_content').html(html.join(''));  
             }
        })    
     });

     $("#btn-print-report").click(function(){
       let reptype = $("#lst-reptype").val(); 
       let machineid = $("#lst-machineid").val(); 

       let date_range = $("#lst_date_range").val();
       let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
       let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
     
       let categorycode = $("#lst-categorycode").val();
       let postedby = $("#lst-postedby").val();
       let recycleby = $("#lst-recycleby").val();
       let recstatus = $("#lst-recstatus").val();

       let generatedby = $("#txt-generatedby").val();
       window.open("extensions/tcpdf/pdf/recycleprint.php?reptype="+reptype+"&machineid="+machineid+"&start_date="+start_date+"&end_date="+end_date+"&categorycode="+categorycode+"&postedby="+postedby+"&recycleby="+recycleby+"&recstatus="+recstatus+"&generatedby="+generatedby+"&user_type="+user_type, "_blank");
    });     
     
     $("#btn-export").click(function(){
        exportToExcel();
     });   

     function exportToExcel() {
        var location = 'data:application/vnd.ms-excel;base64,';
        var excelTemplate = '<html> ' +
        '<head> ' +
        '<meta http-equiv="content-type" content="text/plain; charset=UTF-8"/> ' +
        '</head> ' +
        '<body> ' +
        document.getElementById("release_content").innerHTML +
        '</body> ' +
        '</html>'
        window.location.href = location + window.btoa(excelTemplate);
     }
  });