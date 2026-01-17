$(function() {
    $('#lst_date_range').daterangepicker({
      ranges:{
        'Today'         : [moment(),moment()],
        'Yesterday'     : [moment().subtract(1,'days'), moment().subtract(1,'days')],
        'Last 7 Days'   : [moment().subtract(6,'days'), moment()],
        'This Month'    : [moment().startOf('month'), moment().endOf('month')]
      }
    })
  
     $("#lst-postatus").val('Posted').trigger('change');  
  
     $("#lbl-lst-machineid").click(function(){
        $("#lst-machineid").val('').trigger('change');
     }); 
  
     $("#lbl-lst-categorycode").click(function(){
        $("#lst-categorycode").val('').trigger('change');
     });     
  
    //  $("#lbl-lst-preparedby").click(function(){
    //     $("#lst-preparedby").val('').trigger('change');
    //  });

     $("#lbl-lst-suppliercode").click(function(){
        $("#lst-suppliercode").val('').trigger('change');
     });

     $("#lbl-lst-orderedby").click(function(){
        $("#lst-orderedby").val('').trigger('change');
     });     
  
     $("#lbl-lst-postatus").click(function(){
        $("#lst-postatus").val('').trigger('change');
     });
  
     $('#lst-machineid, #lst_date_range, #lst-categorycode, #lst-suppliercode, #lst-orderedby, #lst-postatus, #lst-reptype').on("change", function(){
        $("#btn-print-report").prop('disabled', false);
  
        let machineid = $('#lst-machineid').val();
        let date_range = $("#lst_date_range").val();
        let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
        let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
  
        let suppliercode = $("#lst-suppliercode").val();
        let orderedby = $("#lst-orderedby").val();
        let categorycode = $("#lst-categorycode").val();
        let postatus = $("#lst-postatus").val();
        let reptype = $("#lst-reptype").val();
  
        var data = new FormData();
        data.append("machineid", machineid);
        data.append("start_date", start_date);
        data.append("end_date", end_date);
        data.append("categorycode", categorycode);
        data.append("suppliercode", suppliercode);
        data.append("orderedby", orderedby);
        data.append("postatus", postatus);
        data.append("reptype", reptype);
  
        $.ajax({
             url:"ajax/purchase_report.ajax.php",   
             method: "POST",                
             data: data,                    
             cache: false,                  
             contentType: false,            
             processData: false,            
             dataType:"json",               
             success:function(answer){
                $(".purchase_content").empty();
                var html = [];
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
                                  var purchase = answer[i];
                                  var catdescription = purchase.catdescription;
                                  var total_qty = numberWithCommas(purchase.total_qty);
                                  var total_amount = numberWithCommas(purchase.total_amount);
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
                                var purchase = answer[i];
                                var catdescription = purchase.catdescription;
                                var meas2 = purchase.meas2;
                                var pdesc = purchase.prodname + ' (' + meas2.toUpperCase() + ')';
  
                                if (purchase.prodname == null){
                                  pdesc = '';
                                  catdescription = '';
                                }else{
                                  if (i == 0){
                                    var prev_catdescription = purchase.catdescription;
                                  }else{
                                    var curr_catdescription = purchase.catdescription;
                                    if (prev_catdescription == curr_catdescription){
                                      catdescription = '';
                                    }
                                    var prev_catdescription = curr_catdescription;
                                  }                 
                                }
  
                                var total_qty = numberWithCommas(purchase.total_qty);
                                var total_amount = numberWithCommas(purchase.total_amount);
                                html.push('<tr>');
                                  html.push('<td>'+catdescription+'</td>');

                                  if (i == answer.length - 1){
                                    html.push('<td style="font-size:1.2em;font-weight:bold;">OVERALL AMOUNT</td>');
                                  }else{
                                    html.push('<td>'+pdesc+'</td>');
                                  }

                                  // html.push('<td>'+pdesc+'</td>');
                                  if (purchase.prodname == null){
                                    html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;"></td>');
                                    // html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+total_qty+'</td>');
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
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">PO #</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Machine</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Supplier</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Ordered by</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Items</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Qty</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Cost</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                          html.push('</tr>');
                          html.push('</thead>');
  
                          for(var i = 0; i < answer.length; i++) {
                              var purchase = answer[i];
  
                              var po_date = purchase.podate;
                              var podate = po_date.substring(5, 7) + '/' + po_date.substring(8, 10) + '/' + po_date.substring(0, 4);
  
                              var ponumber = purchase.ponumber;
                              var suppliername = purchase.name;
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
                                ponumber = '';
                                suppliername = '';
                                machine = '';
                                purchaser = '';
                                ordername = '';
                                pdesc = '';
                                podate = '';
                              }else{
                                if (i == 0){
                                  var prev_ponumber = purchase.ponumber;
                                  var prev_podate = purchase.podate;
                                }else{
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
                                html.push('<td>'+podate+'</td>');
  
                                if (postatus == 'Void'){
                                  html.push('<td style="color:orange;">'+ponumber+'</td>');
                                }else{
                                  html.push('<td>'+ponumber+'</td>');
                                }

                                html.push('<td>'+machine+'</td>'); 
                                html.push('<td>'+suppliername+'</td>'); 
                                html.push('<td>'+ordername+'</td>');
  
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
                                  var purchase = answer[i];
                                  var machinedesc = purchase.machinedesc;
                                  var machabbr = purchase.machabbr;
                                  var buildingname = purchase.buildingname;
  
                                  var machbldg = purchase.machinedesc + purchase.buildingname + purchase.machabbr;
  
                                  var prodname = purchase.prodname;
                                  var meas2 = purchase.meas2;
                                  var pdesc = purchase.prodname + ' (' + meas2.toUpperCase() + ')';
  
                                  var qty = numberWithCommas(purchase.qty);
                                  var tamount = numberWithCommas(purchase.tamount);
  
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
                              var purchase = answer[i];
                              var machinedesc = purchase.machinedesc;
                              var machabbr = purchase.machabbr;
                              var buildingname = purchase.buildingname;

                              var machbldg = purchase.machinedesc + purchase.buildingname + purchase.machabbr;

                              var po_date = purchase.podate;
                              var podate = po_date.substring(5, 7) + '/' + po_date.substring(8, 10) + '/' + po_date.substring(0, 4);
                              
                              var name = purchase.name;
                              var ordername = purchase.ordername;

                              var prodname = purchase.prodname;
                              var meas1 = purchase.meas1;
                              var pdesc = purchase.prodname + ' (' + meas1.toUpperCase() + ')';

                              var qty = numberWithCommas(purchase.qty);

                              if (prodname == null){
                                machinedesc = '';
                                machabbr = '';
                                buildingname = '';
                                podate = '';
                                name = '';
                                ordername = '';
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
                                html.push('<td>'+podate+'</td>');
                                html.push('<td>'+name+'</td>');
                                html.push('<td>'+ordername+'</td>');

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
                $('.purchase_content').html(html.join(''));  
             }
        })    
     });    
  });