$(function() {
    $("#lbl-lst-categorycode").click(function(){
      $("#lst-categorycode").val('').trigger('change');
    }); 

    $('#lst-filter, #lst-reptype, #lst-categorycode').on("change", function(){
        $("#btn-print-report").prop('disabled', false);
        var reptype = $("#lst-reptype").val();
        var filter = $("#lst-filter").val();
        var categorycode = $("#lst-categorycode").val();
  
        var data = new FormData();
        data.append("reptype", reptype);
        data.append("filter", filter);
        data.append("categorycode", categorycode);

        $.ajax({
            url:"ajax/itemreport.ajax.php",   
            method: "POST",                
            data: data,                    
            cache: false,                  
            contentType: false,            
            processData: false,            
            dataType:"json",               
            success:function(answer){
                $(".item_content").empty();
                var html = [];
                // Categorical Display
                if (reptype == 1){
                      html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                        html.push('<table class="table mx-auto w-auto">');
                            html.push('<thead>');
                              html.push('<tr>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Category</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;max-width:100px;">Description</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Pur Unit</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">EQ Qty</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">SKU</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Unit Cost</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Re-order</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">In-stock</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Value</th>');
                              html.push('</tr>');
                            html.push('</thead>');
                            
                            var item_value = 0.00;
                            for(var i = 0; i < answer.length; i++) {
                                var itm = answer[i];
                                var itemid = itm.itemid;
                                var catdescription = itm.catdescription;
                                var pdesc = itm.pdesc;
                                var meas1 = itm.meas1;
                                var eqnum = numberWithCommas(itm.eqnum);
                                var meas2 = itm.meas2;
                                var ucost = numberWithCommas(itm.ucost);
                                var reorder = numberWithCommas(itm.reorder);
                                var onhand = numberWithCommas(itm.onhand);

                                if (eqnum == '1.00'){
                                    eqnum = '';
                                    meas2 = '';
                                }

                                if (reorder == '0.00'){
                                    reorder = '';
                                }

                                item_value = Number(itm.ucost) * Number(itm.onhand);

                                if ((reorder != '')&&(Number(itm.onhand) > Number(itm.reorder))){
                                    html.push('<tr>');
                                        html.push('<td style="border-right:1px solid white">'+catdescription+'</td>');
                                        html.push('<td style="border-right:1px solid white">'+pdesc+'</td>');
                                        html.push('<td>'+meas1+'</td>');
                                        html.push('<td>'+eqnum+'</td>');
                                        html.push('<td style="border-right:1px solid white">'+meas2+'</td>');
                                        html.push('<td style="border-right:1px solid white">'+ucost+'</td>');
                                        html.push('<td style="border-right:1px solid white">'+reorder+'</td>');
                                        html.push('<td style="border-right:1px solid white;color:lightgreen;">'+onhand+'</td>');
                                        html.push('<td>'+numberWithCommas(item_value)+'</td>');
                                    html.push('</tr>');   
                                }else{
                                    html.push('<tr>');
                                        html.push('<td style="border-right:1px solid white">'+catdescription+'</td>');
                                        html.push('<td style="border-right:1px solid white">'+pdesc+'</td>');
                                        html.push('<td>'+meas1+'</td>');
                                        html.push('<td>'+eqnum+'</td>');
                                        html.push('<td style="border-right:1px solid white">'+meas2+'</td>');
                                        html.push('<td style="border-right:1px solid white">'+ucost+'</td>');
                                        html.push('<td style="border-right:1px solid white">'+reorder+'</td>');
                                        html.push('<td style="border-right:1px solid white;color:orangered;">'+onhand+'</td>');
                                        html.push('<td>'+numberWithCommas(item_value)+'</td>');
                                    html.push('</tr>'); 
                                } 
                            }

                            // html.push('<tr>');
                            //     html.push('<td>'+reorder+'</td>');
                            //     html.push('<td>'+onhand+'</td>');
                            // html.push('</tr>');   

                        html.push('</table>');
                      html.push('</div>');
                }else if ((reptype == 2)||(reptype == 3)){
                    html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                     html.push('<table class="table mx-auto w-auto">');
                        html.push('<thead>');
                          html.push('<tr>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Description</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Pur Unit</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">EQ Qty</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">SKU</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Unit Cost</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Re-order</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">In-stock</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Value</th>');
                          html.push('</tr>');
                        html.push('</thead>');
                        var item_value = 0.00;
                        for(var i = 0; i < answer.length; i++) {
                            var itm = answer[i];
                            var itemid = itm.itemid;
                            var pdesc = itm.pdesc;
                            var meas1 = itm.meas1;
                            var eqnum = numberWithCommas(itm.eqnum);
                            var meas2 = itm.meas2;
                            var ucost = numberWithCommas(itm.ucost);
                            var reorder = numberWithCommas(itm.reorder);
                            var onhand = numberWithCommas(itm.onhand);

                            if (eqnum == '1.00'){
                                eqnum = '';
                                meas2 = '';
                            }

                            if (reorder == '0.00'){
                                reorder = '';
                            }

                            item_value = Number(itm.ucost) * Number(itm.onhand);
                            if ((reorder != '')&&(Number(itm.onhand) > Number(itm.reorder))){
                                html.push('<tr>');
                                    html.push('<td style="border-right:1px solid white">'+pdesc+'</td>');
                                    html.push('<td>'+meas1+'</td>');
                                    html.push('<td>'+eqnum+'</td>');
                                    html.push('<td style="border-right:1px solid white">'+meas2+'</td>');
                                    html.push('<td style="border-right:1px solid white">'+ucost+'</td>');
                                    html.push('<td style="border-right:1px solid white">'+reorder+'</td>');
                                    html.push('<td style="border-right:1px solid white;color:lightgreen;">'+onhand+'</td>');
                                    html.push('<td>'+numberWithCommas(item_value)+'</td>');
                                html.push('</tr>');   
                            }else{
                                html.push('<tr>');
                                    html.push('<td style="border-right:1px solid white">'+pdesc+'</td>');
                                    html.push('<td>'+meas1+'</td>');
                                    html.push('<td>'+eqnum+'</td>');
                                    html.push('<td style="border-right:1px solid white">'+meas2+'</td>');
                                    html.push('<td style="border-right:1px solid white">'+ucost+'</td>');
                                    html.push('<td style="border-right:1px solid white">'+reorder+'</td>');
                                    html.push('<td style="border-right:1px solid white;color:orangered;">'+onhand+'</td>');
                                    html.push('<td>'+numberWithCommas(item_value)+'</td>');
                                html.push('</tr>'); 
                            }    
                        }
                    html.push('</table>');
                  html.push('</div>');
                }
                $('.item_content').html(html.join('')); 
            }
        });    
    });
});