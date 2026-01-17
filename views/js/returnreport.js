$(function() {
    $('#lst_date_range').daterangepicker({
      ranges:{
        'Today'         : [moment(),moment()],
        'Yesterday'     : [moment().subtract(1,'days'), moment().subtract(1,'days')],
        'Last 7 Days'   : [moment().subtract(6,'days'), moment()],
        'This Month'    : [moment().startOf('month'), moment().endOf('month')]
      }
    })
  
     $("#lst-retstatus").val('Posted').trigger('change');  
  
     $("#lbl-lst-categorycode").click(function(){
        $("#lst-categorycode").val('').trigger('change');
     });     
  
     $("#lbl-lst-postedby").click(function(){
        $("#lst-postedby").val('').trigger('change');
     });

     $("#lbl-lst-returnby").click(function(){
        $("#lst-returnby").val('').trigger('change');
     });     
  
     $("#lbl-lst-retstatus").click(function(){
        $("#lst-retstatus").val('').trigger('change');
     });
  
     $('#lst_date_range, #lst-categorycode, #lst-postedby, #lst-returnby, #lst-retstatus, #lst-reptype').on("change", function(){
        $("#btn-print-report").prop('disabled', false);
        let date_range = $("#lst_date_range").val();
        let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
        let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
  
        let postedby = $("#lst-postedby").val();
        let returnby = $("#lst-returnby").val();
        let categorycode = $("#lst-categorycode").val();
        let retstatus = $("#lst-retstatus").val();
        let reptype = $("#lst-reptype").val();
  
        var data = new FormData();
        data.append("start_date", start_date);
        data.append("end_date", end_date);
        data.append("categorycode", categorycode);
        data.append("postedby", postedby);
        data.append("returnby", returnby);
        data.append("retstatus", retstatus);
        data.append("reptype", reptype);
  
        $.ajax({
             url:"ajax/return_report.ajax.php",   
             method: "POST",                
             data: data,                    
             cache: false,                  
             contentType: false,            
             processData: false,            
             dataType:"json",               
             success:function(answer){
                $(".return_content").empty();
                var html = [];
                // Overall Return Category
                if (reptype == 1){
                      html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                        html.push('<table class="table mx-auto w-auto">');
                            html.push('<thead>');
                              html.push('<tr>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Category</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Qty</th>');
                              html.push('</tr>');
                            html.push('</thead>');
  
                              for(var i = 0; i < answer.length; i++) {
                                  var re_turn = answer[i];
                                  var catdescription = re_turn.catdescription;
                                  var total_qty = numberWithCommas(re_turn.total_qty);
                                      html.push('<tr>');
  
                                        if (i == answer.length - 1){
                                          html.push('<td style="font-size:1.1em;font-weight:bold;border-top: 2px solid white;">OVERALL AMOUNT</td>');
                                          html.push('<td style="font-size:1.1em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+total_qty+'</td>');
                                        }else{
                                          html.push('<td>'+catdescription+'</td>');
                                          html.push('<td style="text-align:right;">'+total_qty+'</td>');
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
                              html.push('</tr>');
                            html.push('</thead>');
  
                            for(var i = 0; i < answer.length; i++) {
                                var re_turn = answer[i];
                                var catdescription = re_turn.catdescription;
                                var meas1 = re_turn.meas1;
                                var pdesc = re_turn.prodname + ' (' + meas1.toUpperCase() + ')';
  
                                if (re_turn.prodname == null){
                                  pdesc = '';
                                  catdescription = '';
                                }else{
                                  if (i == 0){
                                    var prev_catdescription = re_turn.catdescription;
                                  }else{
                                    var curr_catdescription = re_turn.catdescription;
                                    if (prev_catdescription == curr_catdescription){
                                      catdescription = '';
                                    }
                                    var prev_catdescription = curr_catdescription;
                                  }                 
                                }
  
                                var total_qty = numberWithCommas(re_turn.total_qty);
                                html.push('<tr>');
                                  html.push('<td>'+catdescription+'</td>');
                                  html.push('<td>'+pdesc+'</td>');
                                  if (re_turn.prodname == null){
                                    html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+total_qty+'</td>');
                                  }else{
                                    html.push('<td style="text-align:right;">'+total_qty+'</td>');
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
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Received by</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Returned by</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Items</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Qty</th>');
                          html.push('</tr>');
                          html.push('</thead>');
  
                          for(var i = 0; i < answer.length; i++) {
                              var re_turn = answer[i];
  
                              var rel_date = re_turn.retdate;
                              var retdate = rel_date.substring(5, 7) + '/' + rel_date.substring(8, 10) + '/' + rel_date.substring(0, 4);
  
                              var retnumber = re_turn.retnumber;
                              var retstatus = re_turn.retstatus;
                              var name = re_turn.name;
                              var retname = re_turn.retname;
  
                              var prodname = re_turn.prodname;
                              var meas1 = re_turn.meas1;
                              var pdesc = re_turn.prodname + ' (' + meas1.toUpperCase() + ')';
  
                              var qty = numberWithCommas(re_turn.qty);
  
                              if (prodname == null){
                                retnumber = '';
                                name = '';
                                retname = '';
                                pdesc = '';
                                retdate = '';
                              }else{
                                if (i == 0){
                                  var prev_retnumber = re_turn.retnumber;
                                  var prev_retdate = re_turn.retdate;
                                }else{
                                  var curr_retnumber = re_turn.retnumber;
                                  if (prev_retnumber == curr_retnumber){
                                    retnumber = '';
                                    name = '';
                                    retname = '';
                                  }
                                  var prev_retnumber = curr_retnumber;
                                  // don't display same date
                                  var curr_retdate = re_turn.retdate;
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
 
                                html.push('<td>'+name+'</td>'); 
                                html.push('<td>'+retname+'</td>');
  
                                if (i == answer.length - 1){
                                  html.push('<td style="font-size:1.2em;font-weight:bold;">OVERALL QTY</td>');
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
               $('.return_content').html(html.join(''));  
             }
        })    
     });    
  });