$(function() {
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
  
     $("#lst-retstatus").val('Posted').trigger('change');  
  
     $("#lbl-lst-machineid").click(function(){
        $("#lst-machineid").val('').trigger('change');
     }); 
  
     $("#lbl-lst-categorycode").click(function(){
        $("#lst-categorycode").val('').trigger('change');
     });     
  
     $("#lbl-lst-postedby").click(function(){
        $("#lst-postedby").val('').trigger('change');
     });

     $("#lbl-lst-returnby").click(function(){
        $("#lst-returnby").val('').trigger('change');
     });   
     
     $("#lbl-lst-returntype").click(function(){
        $("#lst-returntype").val('').trigger('change');
     });      
  
     $("#lbl-lst-retstatus").click(function(){
        $("#lst-retstatus").val('').trigger('change');
     });
  
     $('#lst-machineid, #lst_date_range, #lst-categorycode, #lst-postedby, #lst-returnby, #lst-retstatus, #lst-reptype, #lst-returntype').on("change", function(){
        $("#btn-print-report").prop('disabled', false);
        $("#btn-export").prop('disabled', false);
  
        let machineid = $('#lst-machineid').val();
        let date_range = $("#lst_date_range").val();
        let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
        let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
  
        let postedby = $("#lst-postedby").val();
        let returnby = $("#lst-returnby").val();
        let categorycode = $("#lst-categorycode").val();
        let retstatus = $("#lst-retstatus").val();
        let reptype = $("#lst-reptype").val();
        let returntype = $("#lst-returntype").val();
  
        var data = new FormData();
        data.append("machineid", machineid);
        data.append("start_date", start_date);
        data.append("end_date", end_date);
        data.append("categorycode", categorycode);
        data.append("postedby", postedby);
        data.append("returnby", returnby);
        data.append("retstatus", retstatus);
        data.append("reptype", reptype);
        data.append("returntype", returntype);
  
        $.ajax({
             url:"ajax/matreturn_report.ajax.php",   
             method: "POST",                
             data: data,                    
             cache: false,                  
             contentType: false,            
             processData: false,            
             dataType:"json",               
             success:function(answer){
                $(".returns_content").empty();
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
                                  var returns = answer[i];
                                  var catdescription = returns.catdescription;
                                  var total_qty = numberWithCommas(returns.total_qty);
                                  var total_amount = numberWithCommas(returns.total_amount);
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
                                var returns = answer[i];
                                var catdescription = returns.catdescription;
                                var meas2 = returns.meas2;
                                var pdesc = returns.prodname + ' (' + meas2.toUpperCase() + ')';
  
                                if (returns.prodname == null){
                                  pdesc = '';
                                  catdescription = '';
                                }else{
                                  if (i == 0){
                                    var prev_catdescription = returns.catdescription;
                                  }else{
                                    var curr_catdescription = returns.catdescription;
                                    if (prev_catdescription == curr_catdescription){
                                      catdescription = '';
                                    }
                                    var prev_catdescription = curr_catdescription;
                                  }                 
                                }
  
                                var total_qty = numberWithCommas(returns.total_qty);
                                var total_amount = numberWithCommas(returns.total_amount);
                                html.push('<tr>');
                                  html.push('<td>'+catdescription+'</td>');

                                  if (i == answer.length - 1){
                                    html.push('<td style="font-size:1.2em;font-weight:bold;">OVERALL AMOUNT</td>');
                                  }else{
                                    html.push('<td>'+pdesc+'</td>');
                                  }

                                  // html.push('<td>'+pdesc+'</td>');
                                  if (returns.prodname == null){
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
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Rel #</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Machine</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Posted by</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Operated by</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Items</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Qty</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Cost</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                          html.push('</tr>');
                          html.push('</thead>');
  
                          for(var i = 0; i < answer.length; i++) {
                              var returns = answer[i];
  
                              var ret_date = returns.retdate;
                              var retdate = ret_date.substring(5, 7) + '/' + ret_date.substring(8, 10) + '/' + ret_date.substring(0, 4);
  
                              var retnumber = returns.retnumber;
                              var machabbr = returns.machabbr;
                              var machinedesc = returns.machinedesc;
                              var retstatus = returns.retstatus;
                              var name = returns.name;
                              var retname = returns.retname;
  
                              var prodname = returns.prodname;
                              var meas2 = returns.meas2;
                              var pdesc = returns.prodname + ' (' + meas2.toUpperCase() + ')';
  
                              var qty = numberWithCommas(returns.qty);
                              var price = numberWithCommas(returns.price);
                              var tamount = numberWithCommas(returns.tamount);

                              if (machabbr != ''){
                                var machine = machinedesc + ' (' + machabbr + ')';
                              }else{
                                var machine = '';
                              }
  
                              if (prodname == null){
                                retnumber = '';
                                machine = '';
                                name = '';
                                retname = '';
                                pdesc = '';
                                retdate = '';
                              }else{
                                if (i == 0){
                                  var prev_retnumber = returns.retnumber;
                                  var prev_retdate = returns.retdate;
                                }else{
                                  var curr_retnumber = returns.retnumber;
                                  if (prev_retnumber == curr_retnumber){
                                    retnumber = '';
                                    machine = '';
                                    name = '';
                                    retname = '';
                                  }
                                  var prev_retnumber = curr_retnumber;
                                  // don't display same date
                                  var curr_retdate = returns.retdate;
                                  if (prev_retdate == curr_retdate){
                                    retdate = '';
                                  }
                                  var prev_retdate = curr_retdate;                    
                                }                 
                              }
  
                              html.push('<tr>');
                                html.push('<td>'+retdate+'</td>');
  
                                if (retstatus == 'Void'){
                                  html.push('<td style="color:orange;">'+retnumber+'</td>');
                                }else{
                                  html.push('<td>'+retnumber+'</td>');
                                }

                                html.push('<td>'+machine+'</td>'); 
                                html.push('<td>'+name+'</td>'); 
                                html.push('<td>'+retname+'</td>');
  
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
                                  var returns = answer[i];
                                  var machinedesc = returns.machinedesc;
                                  var machabbr = returns.machabbr;
                                  var buildingname = returns.buildingname;
  
                                  var machbldg = returns.machinedesc + returns.buildingname + returns.machabbr;
  
                                  var prodname = returns.prodname;
                                  var meas2 = returns.meas2;
                                  var pdesc = returns.prodname + ' (' + meas2.toUpperCase() + ')';
  
                                  var qty = numberWithCommas(returns.qty);
                                  var tamount = numberWithCommas(returns.tamount);
  
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
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Ret #</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Posted by</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Operated by</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Items</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Qty</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Cost</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                          html.push('</tr>');
                          html.push('</thead>');
                          
                          for(var i = 0; i < answer.length; i++) {
                              var returns = answer[i];
  
                              var ret_date = returns.retdate;
                              var retdate = ret_date.substring(5, 7) + '/' +ret_date.substring(8, 10) + '/' + ret_date.substring(0, 4);
                              var retnumber = returns.retnumber;
                              var machabbr = returns.machabbr;
                              var machinedesc = returns.machinedesc;
                              var retstatus = returns.retstatus;
                              var name = returns.name;
                              var retname = returns.retname;
  
                              var prodname = returns.prodname;
                              var meas2 = returns.meas2;
                              var pdesc = returns.prodname + ' (' + meas2.toUpperCase() + ')';
  
                              var qty = numberWithCommas(returns.qty);
                              var price = numberWithCommas(returns.price);
                              var tamount = numberWithCommas(returns.tamount);

                              // var num_items = returns.num_items;

                              if (machabbr != ''){
                                var machine = machinedesc + ' (' + machabbr + ')';
                              }else{
                                var machine = '';
                              }
  
                              if (prodname == null){
                                retnumber = '';
                                machine = '';
                                name = '';
                                retname = '';
                                pdesc = '';
                                retdate = '';
                              }else{
                                if (i == 0){
                                  var prev_retnumber = returns.retnumber;
                                  var prev_machine = returns.machinedesc;
                                }else{
                                  var curr_retnumber = returns.retnumber;
                                  if (prev_retnumber == curr_retnumber){
                                    retnumber = '';
                                    machine = '';
                                    name = '';
                                    retname = '';
                                    retdate = '';
                                  }
                                  var prev_retnumber = curr_retnumber;
                                  // don't display same date
                                  var curr_machine = returns.machinedesc;
                                  if (prev_machine == curr_machine){
                                    machine = '';
                                  }
                                  var prev_machine = curr_machine;                    
                                }                 
                              }
  
                              // if (prodname != null){
                              html.push('<tr>');
                                html.push('<td>'+machine+'</td>'); 
                                html.push('<td>'+retdate+'</td>');
  
                                if (retstatus == 'Void'){
                                  html.push('<td style="color:orange;">'+retnumber+'</td>');
                                }else{
                                  html.push('<td>'+retnumber+'</td>');
                                }

                                html.push('<td>'+name+'</td>'); 
                                html.push('<td>'+retname+'</td>');
  
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
               $('.returns_content').html(html.join(''));  
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
       let returnby = $("#lst-returnby").val();
       let retstatus = $("#lst-retstatus").val();

       let generatedby = $("#txt-generatedby").val();
       window.open("extensions/tcpdf/pdf/matreturnprint.php?reptype="+reptype+"&machineid="+machineid+"&start_date="+start_date+"&end_date="+end_date+"&categorycode="+categorycode+"&postedby="+postedby+"&returnby="+returnby+"&retstatus="+retstatus+"&generatedby="+generatedby+"&user_type="+user_type, "_blank");
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
        document.getElementById("returns_content").innerHTML +
        '</body> ' +
        '</html>'
        window.location.href = location + window.btoa(excelTemplate);
     }
  });