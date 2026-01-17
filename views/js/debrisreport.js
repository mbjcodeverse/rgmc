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
  
     $("#lst-debstatus").val('Posted').trigger('change');  
  
     $("#lbl-lst-machineid").click(function(){
        $("#lst-machineid").val('').trigger('change');
     }); 
  
     $("#lbl-lst-categorycode").click(function(){
        $("#lst-categorycode").val('').trigger('change');
     });     
  
     $("#lbl-lst-postedby").click(function(){
        $("#lst-postedby").val('').trigger('change');
     });

     $("#lbl-lst-debrisby").click(function(){
        $("#lst-debrisby").val('').trigger('change');
     });     
  
     $("#lbl-lst-debstatus").click(function(){
        $("#lst-debstatus").val('').trigger('change');
     });
  
     $('#lst-machineid, #lst_date_range, #lst-categorycode, #lst-postedby, #lst-debrisby, #lst-debstatus, #lst-reptype').on("change", function(){
        $("#btn-print-report").prop('disabled', false);
        $("#btn-export").prop('disabled', false);
  
        let machineid = $('#lst-machineid').val();
        let date_range = $("#lst_date_range").val();
        let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
        let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
  
        let postedby = $("#lst-postedby").val();
        let debrisby = $("#lst-debrisby").val();
        let categorycode = $("#lst-categorycode").val();
        let debstatus = $("#lst-debstatus").val();
        let reptype = $("#lst-reptype").val();
  
        var data = new FormData();
        data.append("machineid", machineid);
        data.append("start_date", start_date);
        data.append("end_date", end_date);
        data.append("categorycode", categorycode);
        data.append("postedby", postedby);
        data.append("debrisby", debrisby);
        data.append("debstatus", debstatus);
        data.append("reptype", reptype);
  
        $.ajax({
             url:"ajax/debris_report.ajax.php",   
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
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Rel #</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Machine</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Posted by</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Audited by</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Items</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Qty</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Cost</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                          html.push('</tr>');
                          html.push('</thead>');
  
                          for(var i = 0; i < answer.length; i++) {
                              var release = answer[i];
  
                              var rel_date = release.debdate;
                              var debdate = rel_date.substring(5, 7) + '/' + rel_date.substring(8, 10) + '/' + rel_date.substring(0, 4);
  
                              var debnumber = release.debnumber;
                              var machabbr = release.machabbr;
                              var machinedesc = release.machinedesc;
                              var debstatus = release.debstatus;
                              var name = release.name;
                              var debname = release.debname;
  
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
                                debnumber = '';
                                machine = '';
                                name = '';
                                debname = '';
                                pdesc = '';
                                debdate = '';
                              }else{
                                if (i == 0){
                                  var prev_debnumber = release.debnumber;
                                  var prev_debdate = release.debdate;
                                }else{
                                  var curr_debnumber = release.debnumber;
                                  if (prev_debnumber == curr_debnumber){
                                    debnumber = '';
                                    machine = '';
                                    name = '';
                                    debname = '';
                                  }
                                  var prev_debnumber = curr_debnumber;
                                  // don't display same date
                                  var curr_debdate = release.debdate;
                                  if (prev_debdate == curr_debdate){
                                    debdate = '';
                                  }
                                  var prev_debdate = curr_debdate;                    
                                }                 
                              }
  
                              html.push('<tr>');
                                html.push('<td>'+debdate+'</td>');
  
                                if (debstatus == 'Void'){
                                  html.push('<td style="color:orange;">'+debnumber+'</td>');
                                }else{
                                  html.push('<td>'+debnumber+'</td>');
                                }

                                html.push('<td>'+machine+'</td>'); 
                                html.push('<td>'+name+'</td>'); 
                                html.push('<td>'+debname+'</td>');
  
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
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Code</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Bldg</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Date</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Releaser</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Requestor</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Items</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Qty</th>');
                          html.push('</tr>');
                        html.push('</thead>');

                          for(var i = 0; i < answer.length; i++) {
                              var release = answer[i];
                              var machinedesc = release.machinedesc;
                              var machabbr = release.machabbr;
                              var buildingname = release.buildingname;

                              var machbldg = release.machinedesc + release.buildingname + release.machabbr;

                              var rel_date = release.debdate;
                              var debdate = rel_date.substring(5, 7) + '/' + rel_date.substring(8, 10) + '/' + rel_date.substring(0, 4);
                              
                              var name = release.name;
                              var debname = release.debname;

                              var prodname = release.prodname;
                              var meas1 = release.meas1;
                              var pdesc = release.prodname + ' (' + meas1.toUpperCase() + ')';

                              var qty = numberWithCommas(release.qty);

                              if (prodname == null){
                                machinedesc = '';
                                machabbr = '';
                                buildingname = '';
                                debdate = '';
                                name = '';
                                debname = '';
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
                                html.push('<td>'+debdate+'</td>');
                                html.push('<td>'+name+'</td>');
                                html.push('<td>'+debname+'</td>');

                                if (i == answer.length - 1){
                                  html.push('<td style="font-size:1.2em;font-weight:bold;">OVERALL QUANTITY</td>');
                                }else{
                                  html.push('<td>'+pdesc+'</td>');
                                }

                                if (prodname == null){
                                  html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+qty+'</td>');
                                }else{
                                  html.push('<td style="text-align:right;">'+qty+'</td>');
                                }
                              html.push('</tr>');   
                          }   
                      html.push('</table>');
                  html.push('</div>');
                }
               $('.release_content').html(html.join(''));  
             }
        })    
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