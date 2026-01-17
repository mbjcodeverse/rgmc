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
  
     $("#lst-prodstatus").val('Posted').trigger('change');  
  
     $("#lbl-lst-machineid").click(function(){
        $("#lst-machineid").val('').trigger('change');
     }); 
  
     $("#lbl-lst-categorycode").click(function(){
        $("#lst-categorycode").val('').trigger('change');
     });     
  
     $("#lbl-lst-etype").click(function(){
        $("#lst-etype").val('').trigger('change');
     });

     $("#lbl-lst-operatedby").click(function(){
        $("#lst-operatedby").val('').trigger('change');
     });     
  
     $("#lbl-lst-prodstatus").click(function(){
        $("#lst-prodstatus").val('').trigger('change');
     });
  
     $("#lbl-lst-shift").click(function(){
        $("#lst-shift").val('').trigger('change');
     }); 

     $("#lst-etype").val('Finished Goods').trigger('change');

     $('#lst-machineid, #lst_date_range, #lst-categorycode, #lst-etype, #lst-operatedby, #lst-prodstatus, #lst-reptype, #lst-shift').on("change", function(){
        $("#btn-print-report").prop('disabled', false);
        $("#btn-export").prop('disabled', false);
  
        let machineid = $('#lst-machineid').val();
        let date_range = $("#lst_date_range").val();
        let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
        let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
  
        let etype = $("#lst-etype").val();
        let operatedby = $("#lst-operatedby").val();
        let categorycode = $("#lst-categorycode").val();
        let prodstatus = $("#lst-prodstatus").val();
        let reptype = $("#lst-reptype").val();
        let shift = $("#lst-shift").val();
  
        var data = new FormData();
        data.append("machineid", machineid);
        data.append("start_date", start_date);
        data.append("end_date", end_date);
        data.append("categorycode", categorycode);
        data.append("etype", etype);
        data.append("operatedby", operatedby);
        data.append("prodstatus", prodstatus);
        data.append("reptype", reptype);
        data.append("shift", shift);
  
        $.ajax({
             url:"ajax/quota_report.ajax.php",   
             method: "POST",                
             data: data,                    
             cache: false,                  
             contentType: false,            
             processData: false,            
             dataType:"json",               
             success:function(answer){
                $(".release_content").empty();
                var html = [];
                if (reptype == 1){
                    html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                        html.push('<table class="table mx-auto w-auto">');
                            html.push('<thead>');
                              html.push('<tr>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;font-size:1.2em;padding-right:12px;padding-left:12px;">Rank</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;font-size:1.2em;">Operator</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;font-size:1.2em;">Shifts</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;font-size:1.2em;">Prod Qty</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;font-size:1.2em;">Quota</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;font-size:1.2em;">KPI %</th>');
                                // html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                              html.push('</tr>');
                            html.push('</thead>');

                            for(var i = 0; i < answer.length; i++) {
                                let production = answer[i];
                                let oprname = production.oprname;
                                let shifts = production.shifts;
                                let total_qty = numberWithCommas(production.total_qty);
                                let target_qty = numberWithCommas(production.target_qty);
                                let kpi = numberWithCommas(production.kpi) + " %";

                                html.push('<tr>');
                                  html.push('<td style="text-align:center;border:1px solid white;font-size:1.3em;padding-right:12px;padding-left:12px;">'+(i + 1)+'</td>');
                                  html.push('<td style="font-size:1.2em;">'+oprname+'</td>');
                                  html.push('<td style="text-align:center;font-size:1.2em;">'+shifts+'</td>');
                                  html.push('<td style="text-align:right;font-size:1.2em;">'+total_qty+'</td>');
                                  html.push('<td style="text-align:right;font-size:1.2em;">'+target_qty+'</td>');
                                  html.push('<td style="text-align:right;font-size:1.2em;">'+kpi+'</td>');
                                html.push('</tr>');  
                            }
                        html.push('</table>');
                    html.push('</div>');
                }else if (reptype == 2){
                      html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                        html.push('<table class="table mx-auto w-auto">');
                            html.push('<thead>');
                              html.push('<tr>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Operator</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Shifts</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Category</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Prod Qty</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Quota</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">KPI %</th>');
                                // html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                              html.push('</tr>');
                            html.push('</thead>');
  
                            for(var i = 0; i < answer.length; i++) {
                                var production = answer[i];
                                var oprname = production.oprname;
                                var shifts = production.shifts;
                                var catdescription = production.catdescription;
                                if (production.catdescription == null){
                                  oprname = '';
                                  shifts = '';
                                  catdescription = '';
                                }else{
                                  if (i == 0){
                                    var prev_oprname = production.oprname;
                                  }else{
                                    var curr_oprname = production.oprname;
                                    if (prev_oprname == curr_oprname){
                                      oprname = '';
                                    }
                                    var prev_oprname = curr_oprname;
                                  }                 
                                }
  
                                var total_qty = numberWithCommas(production.total_qty);
                                var target_qty = numberWithCommas(production.target_qty);
                                var kpi = (Number(production.total_qty) / Number(production.target_qty) * 100).toFixed(2) + " %";
                                // var total_amount = numberWithCommas(production.total_amount);
                                html.push('<tr>');
                                  html.push('<td>'+oprname+'</td>');

                                  if (i == answer.length - 1){
                                    html.push('<td colspan="2" style="font-size:1.2em;font-weight:bold;">OVERALL EFFICIENCY</td>');
                                  }else{
                                    html.push('<td>'+shifts+'</td>');
                                    html.push('<td>'+catdescription+'</td>');
                                  }

                                  // html.push('<td>'+pdesc+'</td>');
                                  if (production.catdescription == null){
                                    html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+total_qty+'</td>');
                                    html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+target_qty+'</td>');
                                    html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+kpi+'</td>');
                                  }else{
                                    html.push('<td style="text-align:right;">'+total_qty+'</td>');
                                    html.push('<td style="text-align:right;">'+target_qty+'</td>');
                                    html.push('<td style="text-align:right;">'+kpi+'</td>');
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
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;font-size:1.2em;padding-right:12px;padding-left:12px;">Rank</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;font-size:1.2em;">Machine Category</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;font-size:1.2em;">Shifts</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;font-size:1.2em;">Prod Qty</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;font-size:1.2em;">Quota</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;font-size:1.2em;">KPI %</th>');
                                // html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                              html.push('</tr>');
                            html.push('</thead>');

                            for(var i = 0; i < answer.length; i++) {
                                let production = answer[i];
                                let classname = production.classname;
                                let shifts = production.shifts;
                                let total_qty = numberWithCommas(production.total_qty);
                                let target_qty = numberWithCommas(production.target_qty);
                                let kpi = numberWithCommas(production.kpi) + " %";

                                html.push('<tr>');
                                  html.push('<td style="text-align:center;border:1px solid white;font-size:1.3em;padding-right:12px;padding-left:12px;">'+(i + 1)+'</td>');
                                  html.push('<td style="font-size:1.2em;">'+classname+'</td>');
                                  html.push('<td style="text-align:center;font-size:1.2em;">'+shifts+'</td>');
                                  html.push('<td style="text-align:right;font-size:1.2em;">'+total_qty+'</td>');
                                  html.push('<td style="text-align:right;font-size:1.2em;">'+target_qty+'</td>');
                                  html.push('<td style="text-align:right;font-size:1.2em;">'+kpi+'</td>');
                                html.push('</tr>');  
                            }
                        html.push('</table>');
                    html.push('</div>');
                }else if (reptype == 4){
                      html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                        html.push('<table class="table mx-auto w-auto">');
                            html.push('<thead>');
                              html.push('<tr>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Category</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Shifts</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Machine</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Prod Qty</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Quota</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">KPI %</th>');
                                // html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                              html.push('</tr>');
                            html.push('</thead>');
  
                            for(var i = 0; i < answer.length; i++) {
                                var production = answer[i];
                                var classname = production.classname;
                                var shifts = production.shifts;
                                var machinedesc = production.machinedesc;
                                if (production.machinedesc == null){
                                  classname = '';
                                  shifts = '';
                                  machinedesc = '';
                                }else{
                                  if (i == 0){
                                    var prev_classname = production.classname;
                                  }else{
                                    var curr_classname = production.classname;
                                    if (prev_classname == curr_classname){
                                      classname = '';
                                    }
                                    var prev_classname = curr_classname;
                                  }                 
                                }
  
                                var total_qty = numberWithCommas(production.total_qty);
                                var target_qty = numberWithCommas(production.target_qty);
                                var kpi = (Number(production.total_qty) / Number(production.target_qty) * 100).toFixed(2) + " %";
                                // var total_amount = numberWithCommas(production.total_amount);
                                html.push('<tr>');
                                  html.push('<td>'+classname+'</td>');

                                  if (i == answer.length - 1){
                                    html.push('<td colspan="2" style="font-size:1.2em;font-weight:bold;">OVERALL EFFICIENCY</td>');
                                  }else{
                                    html.push('<td>'+shifts+'</td>');
                                    html.push('<td>'+machinedesc+'</td>');
                                  }

                                  // html.push('<td>'+pdesc+'</td>');
                                  if (production.machinedesc == null){
                                    html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+total_qty+'</td>');
                                    html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+target_qty+'</td>');
                                    html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+kpi+'</td>');
                                  }else{
                                    html.push('<td style="text-align:right;">'+total_qty+'</td>');
                                    html.push('<td style="text-align:right;">'+target_qty+'</td>');
                                    html.push('<td style="text-align:right;">'+kpi+'</td>');
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
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Shifts</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Items</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Prod Qty</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Quota</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">KPI %</th>');
                                // html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                              html.push('</tr>');
                            html.push('</thead>');
  
                            for(var i = 0; i < answer.length; i++) {
                                var production = answer[i];
                                var machinedesc = production.machinedesc;
                                var shifts = production.shifts;
                                var pdesc = production.pdesc;
                                if (production.pdesc == null){
                                  machinedesc = '';
                                  shifts = '';
                                  pdesc = '';
                                }else{
                                  if (i == 0){
                                    var prev_machinedesc = production.machinedesc;
                                  }else{
                                    var curr_machinedesc = production.machinedesc;
                                    if (prev_machinedesc == curr_machinedesc){
                                      machinedesc = '';
                                    }
                                    var prev_machinedesc = curr_machinedesc;
                                  }                 
                                }
  
                                var total_qty = numberWithCommas(production.total_qty);
                                var target_qty = numberWithCommas(production.target_qty);
                                var kpi = (Number(production.total_qty) / Number(production.target_qty) * 100).toFixed(2) + " %";
                                // var total_amount = numberWithCommas(production.total_amount);
                                html.push('<tr>');
                                  html.push('<td>'+machinedesc+'</td>');

                                  if (i == answer.length - 1){
                                    html.push('<td colspan="2" style="font-size:1.2em;font-weight:bold;">OVERALL EFFICIENCY</td>');
                                  }else{
                                    html.push('<td>'+shifts+'</td>');
                                    html.push('<td>'+pdesc+'</td>');
                                  }

                                  // html.push('<td>'+pdesc+'</td>');
                                  if (production.pdesc == null){
                                    html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+total_qty+'</td>');
                                    html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+target_qty+'</td>');
                                    html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+kpi+'</td>');
                                  }else{
                                    html.push('<td style="text-align:right;">'+total_qty+'</td>');
                                    html.push('<td style="text-align:right;">'+target_qty+'</td>');
                                    html.push('<td style="text-align:right;">'+kpi+'</td>');
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

    $("#btn-print-report").click(function(){
      let reptype = $("#lst-reptype").val(); 
      let machineid = $("#lst-machineid").val(); 

      let date_range = $("#lst_date_range").val();
      let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
      let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
     
      let categorycode = $("#lst-categorycode").val();
      let etype = $("#lst-etype").val();
      let operatedby = $("#lst-operatedby").val();
      let prodstatus = $("#lst-prodstatus").val();
      let shift = $("#lst-shift").val();

      let generatedby = $("#txt-generatedby").val();

      window.open("extensions/tcpdf/pdf/prodfinprint.php?reptype="+reptype+"&machineid="+machineid+"&start_date="+start_date+"&end_date="+end_date+"&categorycode="+categorycode+"&etype="+etype+"&operatedby="+operatedby+"&prodstatus="+prodstatus+"&generatedby="+generatedby+"&user_type="+user_type+"&shift="+shift, "_blank");
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