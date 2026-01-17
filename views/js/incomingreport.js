$(function() {
  $('#lst_date_range').daterangepicker({
    ranges:{
      'Today'         : [moment(),moment()],
      'Yesterday'     : [moment().subtract(1,'days'), moment().subtract(1,'days')],
      'Last 7 Days'   : [moment().subtract(6,'days'), moment()],
      'This Month'    : [moment().startOf('month'), moment().endOf('month')]
    }
  })

   $("#lst-delstatus").val('Posted').trigger('change');  

   $("#lbl-lst-machineid").click(function(){
      $("#lst-machineid").val('').trigger('change');
   }); 

   $("#lbl-lst-categorycode").click(function(){
      $("#lst-categorycode").val('').trigger('change');
   });     

   $("#lbl-lst-suppliercode").click(function(){
      $("#lst-suppliercode").val('').trigger('change');
   });

   $("#lbl-lst-delstatus").click(function(){
      $("#lst-delstatus").val('').trigger('change');
   });

   $('#lst-machineid, #lst_date_range, #lst-categorycode , #lst-suppliercode, #lst-delstatus, #lst-reptype').on("change", function(){
      $("#btn-print-report").prop('disabled', false);

      let machineid = $('#lst-machineid').val();
      let date_range = $("#lst_date_range").val();
      let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
      let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);

      let suppliercode = $("#lst-suppliercode").val();
      let categorycode = $("#lst-categorycode").val();
      let delstatus = $("#lst-delstatus").val();
      let reptype = $("#lst-reptype").val();

      var data = new FormData();
      data.append("machineid", machineid);
      data.append("start_date", start_date);
      data.append("end_date", end_date);
      data.append("categorycode", categorycode);
      data.append("suppliercode", suppliercode);
      data.append("delstatus", delstatus);
      data.append("reptype", reptype);

      $.ajax({
           url:"ajax/incoming_report.ajax.php",   
           method: "POST",                
           data: data,                    
           cache: false,                  
           contentType: false,            
           processData: false,            
           dataType:"json",               
           success:function(answer){
              $(".incoming_content").empty();
              var html = [];

              // Overall Incoming Category
              if (reptype == 1){
                if (($("#user_type").val() == 'Super Administrator')||($("#user_type").val() == 'Administrator')||($("#user_type").val() == 'Regular')){
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
                                var incoming = answer[i];
                                var catdescription = incoming.catdescription;
                                var total_qty = numberWithCommas(incoming.total_qty);
                                var total_amount = numberWithCommas(incoming.total_amount);
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
                }else{
                    html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                      html.push('<table class="table mx-auto w-auto">');
                          html.push('<thead>');
                            html.push('<tr>');
                              html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Category</th>');
                              html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Qty</th>');
                            html.push('</tr>');
                          html.push('</thead>');

                            for(var i = 0; i < answer.length; i++) {
                                var incoming = answer[i];
                                var catdescription = incoming.catdescription;
                                var total_qty = numberWithCommas(incoming.total_qty);
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
                }
             }else if (reptype == 2){
                if (($("#user_type").val() == 'Super Administrator')||($("#user_type").val() == 'Administrator')||($("#user_type").val() == 'Regular')){
                    html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                      html.push('<table class="table mx-auto w-auto">');
                          html.push('<thead>');
                            html.push('<tr>');
                              html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Category</th>');
                              html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Product</th>');
                              html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Qty</th>');
                              html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                            html.push('</tr>');
                          html.push('</thead>');

                          for(var i = 0; i < answer.length; i++) {
                              var incoming = answer[i];
                              var catdescription = incoming.catdescription;
                              var meas1 = incoming.meas1;
                              var pdesc = incoming.prodname + ' (' + meas1.toUpperCase() + ')';

                              if (incoming.prodname == null){
                                pdesc = '';
                                catdescription = '';
                              }else{
                                if (i == 0){
                                  var prev_catdescription = incoming.catdescription;
                                }else{
                                  var curr_catdescription = incoming.catdescription;
                                  if (prev_catdescription == curr_catdescription){
                                    catdescription = '';
                                  }
                                  var prev_catdescription = curr_catdescription;
                                }                 
                              }

                              var total_qty = numberWithCommas(incoming.total_qty);
                              var total_amount = numberWithCommas(incoming.total_amount);
                              html.push('<tr>');
                                html.push('<td>'+catdescription+'</td>');
                                html.push('<td>'+pdesc+'</td>');
                                if (incoming.prodname == null){
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
                }else{
                    html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                      html.push('<table class="table mx-auto w-auto">');
                          html.push('<thead>');
                            html.push('<tr>');
                              html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Category</th>');
                              html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Product</th>');
                              html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Qty</th>');
                            html.push('</tr>');
                          html.push('</thead>');

                          for(var i = 0; i < answer.length; i++) {
                              var incoming = answer[i];
                              var catdescription = incoming.catdescription;

                              var meas1 = incoming.meas1;
                              var pdesc = incoming.prodname + ' (' + meas1.toUpperCase() + ')';

                              if (incoming.prodname == null){
                                pdesc = '';
                                catdescription = '';
                              }else{
                                if (i == 0){
                                  var prev_catdescription = incoming.catdescription;
                                }else{
                                  var curr_catdescription = incoming.catdescription;
                                  if (prev_catdescription == curr_catdescription){
                                    catdescription = '';
                                  }
                                  var prev_catdescription = curr_catdescription;
                                }                 
                              }

                              var total_qty = numberWithCommas(incoming.total_qty);
                              html.push('<tr>');
                                html.push('<td>'+catdescription+'</td>');
                                html.push('<td>'+pdesc+'</td>');
                                if (incoming.prodname == null){
                                  html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+total_qty+'</td>');
                                }else{
                                  html.push('<td style="text-align:right;">'+total_qty+'</td>');
                                }  
                              html.push('</tr>');
                          }  
                                    
                      html.push('</table>');
                    html.push('</div>');                  
                }                 
             }else if (reptype == 3){
                if (($("#user_type").val() == 'Super Administrator')||($("#user_type").val() == 'Administrator')||($("#user_type").val() == 'Regular')){
                    html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                      html.push('<table class="table mx-auto w-auto">');
                        html.push('<thead>');
                        html.push('<tr>');
                          html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Date</th>');
                          html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Invoice #</th>');
                          html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Supplier</th>');
                          html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Items</th>');
                          html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Qty</th>');
                          html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Price</th>');
                          html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Total</th>');
                        html.push('</tr>');
                        html.push('</thead>');

                        for(var i = 0; i < answer.length; i++) {
                            var incoming = answer[i];

                            var inv_date = incoming.deldate;
                            var deldate = inv_date.substring(5, 7) + '/' + inv_date.substring(8, 10) + '/' + inv_date.substring(0, 4);

                            var delnumber = incoming.delnumber;
                            var delstatus = incoming.delstatus;
                            var name = incoming.name;

                            
                            var prodname = incoming.prodname;
                            var meas1 = incoming.meas1;
                            var pdesc = incoming.prodname + ' (' + meas1.toUpperCase() + ')';

                            var qty = numberWithCommas(incoming.qty);
                            var price = numberWithCommas(incoming.price);
                            var tamount = numberWithCommas(incoming.tamount);

                            if (prodname == null){
                              delnumber = '';
                              name = '';
                              pdesc = '';
                              price = '';
                              deldate = '';
                            }else{
                              if (i == 0){
                                var prev_delnumber = incoming.delnumber;
                                var prev_deldate = incoming.deldate;
                              }else{
                                var curr_delnumber = incoming.delnumber;
                                if (prev_delnumber == curr_delnumber){
                                  delnumber = '';
                                  name = '';
                                }
                                var prev_delnumber = curr_delnumber;
                                // don't display same date
                                var curr_deldate = incoming.deldate;
                                if (prev_deldate == curr_deldate){
                                  deldate = '';
                                }
                                var prev_deldate = curr_deldate;                    
                              }                 
                            }

                            html.push('<tr>');
                              html.push('<td>'+deldate+'</td>');

                              if (delstatus == 'Void'){
                                html.push('<td style="color:orange;">'+delnumber+'</td>');
                              }else{
                                html.push('<td>'+delnumber+'</td>');
                              }
                              html.push('<td>'+name+'</td>'); 

                              if (i == answer.length - 1){
                                html.push('<td style="font-size:1.2em;font-weight:bold;">OVERALL AMOUNT</td>');
                              }else{
                                html.push('<td>'+pdesc+'</td>');
                              }
                              
                              if (prodname == null){
                                 html.push('<td style="text-align:right;"></td>');
                                // html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+qty+'</td>');
                              }else{
                                 html.push('<td style="text-align:right;">'+qty+'</td>');
                              } 

                              html.push('<td style="text-align:right;">'+price+'</td>');

                              if (prodname == null){
                                html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+tamount+'</td>');
                              }else{
                                html.push('<td style="text-align:right;">'+tamount+'</td>');
                              }

                            html.push('</tr>');               
                        }  
                                  
                      html.push('</table>');
                    html.push('</div>');
                }
            }else if (reptype == 4){
                if (($("#user_type").val() == 'Super Administrator')||($("#user_type").val() == 'Administrator')||($("#user_type").val() == 'Regular')){
                    html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                      html.push('<table class="table mx-auto w-auto">');
                          html.push('<thead>');
                            html.push('<tr>');
                              html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Machine</th>');
                              html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Bldg</th>');
                              html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Date</th>');
                              html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Supplier</th>');
                              html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Items</th>');
                              html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Qty</th>');
                              html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Price</th>');
                              html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Total</th>');
                            html.push('</tr>');
                          html.push('</thead>');

                            for(var i = 0; i < answer.length; i++) {
                                var incoming = answer[i];
                                var machinedesc = incoming.machinedesc;
                                var buildingname = incoming.buildingname;

                                var machbldg = incoming.machinedesc + incoming.buildingname;

                                var inv_date = incoming.deldate;
                                var deldate = inv_date.substring(5, 7) + '/' + inv_date.substring(8, 10) + '/' + inv_date.substring(0, 4);
                                
                                var name = incoming.name;

                                var prodname = incoming.prodname;
                                var meas1 = incoming.meas1;
                                var pdesc = incoming.prodname + ' (' + meas1.toUpperCase() + ')';

                                var qty = numberWithCommas(incoming.qty);
                                var price = numberWithCommas(incoming.price);
                                var tamount = numberWithCommas(incoming.tamount);

                                if (prodname == null){
                                  machinedesc = '';
                                  buildingname = '';
                                  deldate = '';
                                  name = '';
                                  pdesc = '';
                                  qty = '';
                                  price = ''
                                }else{
                                  if (i == 0){
                                    var prev_machbldg = machbldg;
                                  }else{
                                    var curr_machbldg = machbldg;
                                    if (prev_machbldg == curr_machbldg){
                                      machinedesc = '';
                                      buildingname = '';
                                    }
                                    var prev_machbldg = curr_machbldg;                    
                                  }                 
                                }

                                html.push('<tr>');
                                  html.push('<td>'+machinedesc+'</td>');
                                  html.push('<td>'+buildingname+'</td>');
                                  html.push('<td>'+deldate+'</td>');
                                  html.push('<td>'+name+'</td>');

                                  if (i == answer.length - 1){
                                    html.push('<td style="font-size:1.2em;font-weight:bold;">OVERALL AMOUNT</td>');
                                  }else{
                                    html.push('<td>'+pdesc+'</td>');
                                  }

                                  if (prodname == null){
                                    html.push('<td style="text-align:right;"></td>');
                                  }else{
                                    html.push('<td style="text-align:right;">'+qty+'</td>');
                                  } 

                                  html.push('<td style="text-align:right;">'+price+'</td>');

                                  if (prodname == null){
                                    html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+tamount+'</td>');
                                  }else{
                                    html.push('<td style="text-align:right;">'+tamount+'</td>');
                                  }
                                html.push('</tr>');   
                            }   
                        html.push('</table>');
                    html.push('</div>');
                }
             }
             $('.incoming_content').html(html.join(''));  
           }
      })    
   });    
});