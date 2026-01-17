$(function() {
    // $('#lst_date_range').daterangepicker({
    //   ranges:{
    //     'Today'         : [moment(),moment()],
    //     'Yesterday'     : [moment().subtract(1,'days'), moment().subtract(1,'days')],
    //     'Last 7 Days'   : [moment().subtract(6,'days'), moment()],
    //     'This Month'    : [moment().startOf('month'), moment().endOf('month')]
    //   }
    // });

    $('#lst_date_range').daterangepicker({
      ranges:{
        'All'           : [moment('2025-12-01'), moment()],
        'Today'         : [moment(),moment()],
        'Yesterday'     : [moment().subtract(1,'days'), moment().subtract(1,'days')],
        'Last 7 Days'   : [moment().subtract(6,'days'), moment()],
        'Last 30 Days'  : [moment().subtract(30,'days'), moment()],
        'This Month'    : [moment().startOf('month'), moment().endOf('month')],
        'Last Month'    : [moment().subtract(1, 'months').startOf('month'), moment().subtract(1, 'months').endOf('month')]
      },
      startDate: moment().startOf('month'), 
      endDate: moment().endOf('month'),
      minDate: moment('2025-12-01')
    });    

    $("#lbl-lst-machineid").click(function(){
        $("#lst-machineid").val('').trigger('change');
    }); 

    $("#lbl-lst-classcode").click(function(){
        $("#lst-classcode").val('').trigger('change');
    });    
    
    $("#lbl-lst-reporter").click(function(){
        $("#lst-reporter").val('').trigger('change');
    });    
    
    $("#lbl-lst-curstatus").click(function(){
        $("#lst-curstatus").val('').trigger('change');
    }); 

    $("#lbl-lst-failuretype").click(function(){
        $("#lst-failuretype").val('').trigger('change');
    }); 

    $("#lbl-lst-shift").click(function(){
        $("#lst-shift").val('').trigger('change');
    });     

    $('#lst-machineid, #lst_date_range, #lst-classcode, #lst-reporter, #lst-curstatus, #lst-failuretype, #lst-shift, #lst-reptype').on("change", function(){
        $("#btn-print-report").prop('disabled', false);
        $("#btn-export").prop('disabled', false);
  
        let machineid = $('#lst-machineid').val();
        let date_range = $("#lst_date_range").val();
        let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
        let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
  
        let classcode = $("#lst-classcode").val();
        let reporter = $("#lst-reporter").val();
        let curstatus = $("#lst-curstatus").val();
        let failuretype = $("#lst-failuretype").val();
        let shift = $("#lst-shift").val();
        let reptype = $("#lst-reptype").val();

        // alert(machineid + '|' + start_date + '|' + end_date + '|' + classcode + '|' + reporter + '|' + curstatus + '|' + failuretype + '|' + shift + '|' + reptype)
  
        var data = new FormData();
        data.append("machineid", machineid);
        data.append("start_date", start_date);
        data.append("end_date", end_date);
        data.append("classcode", classcode);
        data.append("reporter", reporter);
        data.append("curstatus", curstatus);
        data.append("failuretype", failuretype);
        data.append("shift", shift);
        data.append("reptype", reptype);
  
        $.ajax({
             url:"ajax/machine_tracking_report.ajax.php",   
             method: "POST",                
             data: data,                    
             cache: false,                  
             contentType: false,            
             processData: false,            
             dataType:"json",               
             success:function(answer){
                $(".tracking_content").empty();
                var html = [];
                if (reptype == 1){
                      html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                        html.push('<table class="table mx-auto w-auto">');
                            html.push('<thead>');
                              html.push('<tr>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Category</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Frequency</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Downtime (Hrs)</th>');
                              html.push('</tr>');
                            html.push('</thead>');
  
                              for(var i = 0; i < answer.length; i++) {
                                  var tracking = answer[i];
                                  var classname = tracking.classname;
                                  var frequency = numberWithCommasNoDecimal(tracking.frequency);
                                  var totalduration = numberWithCommas(tracking.totalduration);
                                      html.push('<tr>');
  
                                        if (i == answer.length - 1){
                                          html.push('<td style="font-size:1.1em;font-weight:bold;border-top: 2px solid white;">TOTAL DURATION</td>');
                                          html.push('<td style="font-size:1.1em;font-weight:bold;text-align:center;border-top: 2px solid white;">'+frequency+'</td>');
                                          html.push('<td style="font-size:1.1em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+totalduration+'</td>');
                                        }else{
                                          html.push('<td>'+classname+'</td>');
                                          html.push('<td style="text-align:center;">'+frequency+'</td>');
                                          html.push('<td style="text-align:right;">'+totalduration+'</td>');
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
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Machine</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Frequency</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Downtime</th>');
                              html.push('</tr>');
                            html.push('</thead>');
  
                            for(var i = 0; i < answer.length; i++) {
                                var tracking = answer[i];
                                var classname = tracking.classname;
                                var machinedesc = tracking.machinedesc;
  
                                if (tracking.machinedesc == null){
                                  machinedesc = '';
                                  classname = '';
                                }else{
                                  if (i == 0){
                                    var prev_classname = tracking.classname;
                                  }else{
                                    var curr_classname = tracking.classname;
                                    if (prev_classname == curr_classname){
                                      classname = '';
                                    }
                                    var prev_classname = curr_classname;
                                  }                 
                                }
  
                                var frequency = numberWithCommasNoDecimal(tracking.frequency);
                                var totalduration = numberWithCommas(tracking.totalduration);
                                html.push('<tr>');
                                  html.push('<td>'+classname+'</td>');

                                  if (i == answer.length - 1){
                                    html.push('<td style="font-size:1.2em;font-weight:bold;">TOTAL DOWNTIME</td>');
                                  }else{
                                    html.push('<td>'+machinedesc+'</td>');
                                  }

                                  if (tracking.machinedesc == null){
                                    html.push('<td style="font-size:1.2em;font-weight:bold;text-align:center;border-top: 2px solid white;">'+frequency+'</td>');
                                    html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+totalduration+'</td>');
                                  }else{
                                    html.push('<td style="text-align:center;">'+frequency+'</td>');
                                    html.push('<td style="text-align:right;">'+totalduration+'</td>');
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
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Failure Type</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Machine</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Frequency</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Downtime</th>');
                              html.push('</tr>');
                            html.push('</thead>');
  
                            for(var i = 0; i < answer.length; i++) {
                                var tracking = answer[i];
                                var failuretype = tracking.failuretype;
                                var machinedesc = tracking.machinedesc;
  
                                if (tracking.machinedesc == null){
                                  machinedesc = '';
                                  failuretype = '';
                                }else{
                                  if (i == 0){
                                    var prev_failuretype = tracking.failuretype;
                                  }else{
                                    var curr_failuretype = tracking.failuretype;
                                    if (prev_failuretype == curr_failuretype){
                                      failuretype = '';
                                    }
                                    var prev_failuretype = curr_failuretype;
                                  }                 
                                }
  
                                var frequency = numberWithCommasNoDecimal(tracking.frequency);
                                var totalduration = numberWithCommas(tracking.totalduration);
                                html.push('<tr>');
                                  html.push('<td>'+failuretype+'</td>');

                                  if (i == answer.length - 1){
                                    html.push('<td style="font-size:1.2em;font-weight:bold;">TOTAL DOWNTIME</td>');
                                  }else{
                                    html.push('<td>'+machinedesc+'</td>');
                                  }

                                  if (tracking.machinedesc == null){
                                    html.push('<td style="font-size:1.2em;font-weight:bold;text-align:center;border-top: 2px solid white;">'+frequency+'</td>');
                                    html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+totalduration+'</td>');
                                  }else{
                                    html.push('<td style="text-align:center;">'+frequency+'</td>');
                                    html.push('<td style="text-align:right;">'+totalduration+'</td>');
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
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Date Rep</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Time Rep</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Machine</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Fail Type</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Shift</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Cont #</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Date Com</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Time Com</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Downtime</th>');
                          html.push('</tr>');
                          html.push('</thead>');
  
                          for(var i = 0; i < answer.length; i++) {
                              var tracking = answer[i];   
                              var datereported = tracking.datereported;  
                              var inctime = tracking.inctime;
                              var machinedesc = tracking.machinedesc;
                              var failuretype = tracking.failuretype;
                              var shift = tracking.shift;
                              var controlnum = tracking.controlnum;
                              var datecompleted = tracking.datecompleted;
                              var endtime = tracking.endtime;
                              var timeduration = tracking.timeduration;

                              if (endtime == ''){
                                timeduration = '';
                              }

                              html.push('<tr>');
                                html.push('<td>'+datereported+'</td>');
                                html.push('<td>'+inctime+'</td>');
                                html.push('<td>'+machinedesc+'</td>');
                                html.push('<td>'+failuretype+'</td>');
                                html.push('<td>'+shift+'</td>');
                                html.push('<td>'+controlnum+'</td>');
                                html.push('<td>'+datecompleted+'</td>');
                                html.push('<td>'+endtime+'</td>');
                                html.push('<td style="text-align:center;">'+timeduration+'</td>');
                              html.push('</tr>');               
                          }  
                                    
                        html.push('</table>');
                      html.push('</div>');
                }else if (reptype == 5){
                    html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                        html.push('<table class="table mx-auto w-auto">');
                          html.push('<thead>');
                          html.push('<tr>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Month Series</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Frequency</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Downtime</th>');
                          html.push('</tr>');
                          html.push('</thead>');
  
                          for(var i = 0; i < answer.length; i++) {
                              var tracking = answer[i];   
                              var month_year = tracking.month_year;  
                              var frequency = tracking.frequency;
                              var timeduration = tracking.timeduration;

                              html.push('<tr>');
                                html.push('<td>'+month_year+'</td>');
                                html.push('<td style="text-align:center;">'+frequency+'</td>');
                                html.push('<td>'+timeduration+'</td>');
                              html.push('</tr>');               
                          }  
                                    
                        html.push('</table>');
                    html.push('</div>');
                }else if (reptype == 6){
                  html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                    html.push('<table class="table mx-auto w-auto">');
                        html.push('<thead>');
                          html.push('<tr>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Supplier</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Items</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Qty</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                          html.push('</tr>');
                        html.push('</thead>');

                          for(var i = 0; i < answer.length; i++) {
                              var purchase = answer[i];
                              var sname = purchase.name;

                              var prodname = purchase.prodname;
                              var meas2 = purchase.meas2;
                              var pdesc = purchase.prodname + ' (' + meas2.toUpperCase() + ')';

                              var qty = numberWithCommas(purchase.qty);
                              var tamount = numberWithCommas(purchase.tamount);

                              if (prodname == null){
                                sname = '';
                                pdesc = '';
                              }else{
                                if (i == 0){
                                  var prev_sname = sname;
                                }else{
                                  var curr_sname = sname;
                                  if (prev_sname == curr_sname){
                                    sname = '';
                                  }
                                  var prev_sname = curr_sname;                    
                                }                 
                              }

                              html.push('<tr>');
                                html.push('<td>'+sname+'</td>');

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
                }else if (reptype == 7){
                  html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                    html.push('<table class="table mx-auto w-auto">');
                      html.push('<thead>');
                      html.push('<tr>');
                        html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Supplier</th>');
                        html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Date</th>');
                        html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">PO #</th>');
                        html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Machine</th>');
                        html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Ordered by</th>');
                        html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Items</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Qty</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Cost</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                      html.push('</tr>');
                      html.push('</thead>');

                      for(var i = 0; i < answer.length; i++) {
                          var purchase = answer[i];
                          
                          var id = purchase.id;
                          var suppliername = purchase.name;
                          var po_date = purchase.podate;
                          var podate = po_date.substring(5, 7) + '/' + po_date.substring(8, 10) + '/' + po_date.substring(0, 4);

                          var ponumber = purchase.ponumber;
                          var machabbr = purchase.machabbr;
                          var machinedesc = purchase.machinedesc;
                          var postatus = purchase.postatus;
                          var purchaser = purchase.purchaser;
                          var ordername = purchase.ordername;

                          var prodname = purchase.prodname;
                          var meas2 = purchase.meas2;
                          var pdesc = purchase.prodname + ' (' + meas2.toUpperCase() + ')';

                          var qty = numberWithCommas(purchase.qty);
                          var price = numberWithCommas(purchase.price);
                          var tamount = numberWithCommas(purchase.tamount);

                          if (machabbr != ''){
                            var machine = machinedesc + ' (' + machabbr + ')';
                          }else{
                            var machine = '';
                          }

                          if (prodname == null){
                            suppliername = '';
                            ponumber = '';
                            machine = '';
                            purchaser = '';
                            ordername = '';
                            pdesc = '';
                            podate = '';
                          }else{
                            if (i == 0){
                              var prev_ponumber = purchase.ponumber;
                              var prev_podate = purchase.podate;
                              var prev_suppliername = purchase.name;
                            }else{
                              var curr_suppliername = purchase.name;
                              if (prev_suppliername == curr_suppliername){
                                suppliername = '';
                              }
                              var prev_suppliername = curr_suppliername;

                              var curr_ponumber = purchase.ponumber;
                              if (prev_ponumber == curr_ponumber){
                                ponumber = '';
                                suppliername = '';
                                machine = '';
                                purchaser = '';
                                ordername = '';
                              }
                              var prev_ponumber = curr_ponumber;
                              // don't display same date
                              var curr_podate = purchase.podate;
                              if (prev_podate == curr_podate){
                                podate = '';
                              }
                              var prev_podate = curr_podate;                    
                            }                 
                          }

                          html.push('<tr>');
                            html.push('<td>'+suppliername+'</td>');
                            html.push('<td>'+podate+'</td>');

                            if (postatus == 'Void'){
                              html.push('<td style="color:orange;">'+ponumber+'</td>');
                            }else{
                              html.push('<td>'+ponumber+'</td>');
                            }

                            html.push('<td>'+machine+'</td>');  
                            html.push('<td>'+ordername+'</td>');

                            if (i == answer.length - 1){
                              html.push('<td style="font-size:1.2em;font-weight:bold;">OVERALL AMOUNT</td>');
                            }else{
                              html.push('<td>'+pdesc+'</td>');
                            }
                            
                            if (prodname == null){
                               if ((id == null)&&(suppliername != null)){
                                  // html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;"></td>');
                                  html.push('<td colspan="2" style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;border-right: 2px solid white;border-bottom: 2px solid white;color:lightgreen;">SUB-TOTAL</td>');
                                  html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;border-bottom: 2px solid white;color:lightgreen;">'+tamount+'</td>');
                               }else{
                                  html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;"></td>');
                                  html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;"></td>');
                                  html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+tamount+'</td>');
                               }
                            }else{
                               html.push('<td style="text-align:right;">'+qty+'</td>');
                               html.push('<td style="text-align:right;">'+price+'</td>');
                               html.push('<td style="text-align:right;">'+tamount+'</td>');
                            }
                          html.push('</tr>');               
                      }  
                                
                    html.push('</table>');
                  html.push('</div>');
                }
                $('.tracking_content').html(html.join(''));  
             }
        })    
    });    

    function numberWithCommasNoDecimal(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    $("#btn-print-report").click(function(){
      let reptype = $("#lst-reptype").val(); 
      let machineid = $("#lst-machineid").val(); 

      let date_range = $("#lst_date_range").val();
      let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
      let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
     
      let classcode = $("#lst-classcode").val();
      let reporter = $("#lst-reporter").val();
      let curstatus = $("#lst-curstatus").val();
      let failuretype = $("#lst-failuretype").val();
      let shift = $("#lst-shift").val();

      let generatedby = $("#txt-generatedby").val();

      window.open("extensions/tcpdf/pdf/machinetrackingprint.php?reptype="+reptype+"&machineid="+machineid+"&start_date="+start_date+"&end_date="+end_date+"&classcode="+classcode+"&reporter="+reporter+"&curstatus="+curstatus+"&failuretype="+failuretype+"&generatedby="+generatedby+"&user_type="+user_type+"&shift="+shift, "_blank");
    });       

    // $("#btn-print-report").click(function(){
    //   let reptype = $("#lst-reptype").val(); 
    //   let machineid = $("#lst-machineid").val(); 

    //   let date_range = $("#lst_date_range").val();
    //   let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
    //   let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
     
    //   let categorycode = $("#lst-categorycode").val();
    //   let postedby = $("#lst-postedby").val();
    //   let operatedby = $("#lst-operatedby").val();
    //   let prodstatus = $("#lst-prodstatus").val();
    //   let shift = $("#lst-shift").val();

    //   let generatedby = $("#txt-generatedby").val();

    //   window.open("extensions/tcpdf/pdf/prodfinprint.php?reptype="+reptype+"&machineid="+machineid+"&start_date="+start_date+"&end_date="+end_date+"&categorycode="+categorycode+"&postedby="+postedby+"&operatedby="+operatedby+"&prodstatus="+prodstatus+"&generatedby="+generatedby+"&user_type="+user_type+"&shift="+shift, "_blank");
    // });     
     
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
      document.getElementById("tracking_content").innerHTML +
      '</body> ' +
      '</html>'
      window.location.href = location + window.btoa(excelTemplate);
    }    
});