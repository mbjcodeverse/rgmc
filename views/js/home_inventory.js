$(function() {
    var currentDate = new Date();
    var formattedDate = (currentDate.getMonth() + 1).toString().padStart(2, '0') + '/' +
                         currentDate.getDate().toString().padStart(2, '0') + '/' +
                         currentDate.getFullYear();
    $('#date-asofdate').val(formattedDate);

    $('#sel-categorycode, #date-asofdate').on("change", function(){
        generateInventory();
    });

    generateInventory();

    function generateInventory(){
        let categorycode = $("#sel-categorycode").val(); 
        let asof_date = $("#date-asofdate").val();
        let asofdate = asof_date.substring(6, 10) + '-' + asof_date.substring(0, 2) + '-' + asof_date.substring(3, 5);

        var data = new FormData();
        data.append("categorycode", categorycode);
        data.append("asofdate", asofdate);

        $.ajax({
            url:"ajax/inventory_technical.ajax.php",   
            method: "POST",                
            data: data,                    
            cache: false,                  
            contentType: false,            
            processData: false,            
            dataType:"json",               
            success:function(answer){
                $(".inventory_content").empty();
                 var html = [];
                html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;margin-bottom:12px;">');
                    html.push('<table class="table mx-auto w-auto itemInventoryTable">');
                        html.push('<thead>');
                            html.push('<tr>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">#</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">ITEM DESCRIPTION</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">PU</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">EQ QTY</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">SKU</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Cost</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Re-order</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Stock</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Act</th>');
                            html.push('</tr>');
                        html.push('</thead>');

                        var prev_itemid = 0;
                        var curr_itemid = 0;

                        var prev_code = '';
                        var curr_code = '';

                        var ctr = 0;
                        var onhand = 0.00;
                        var isInventory = 0;
                        var itemid = 0;
                        var item_num = 0;

                        var num_rec = answer.length;
                        for(var i = 0; i < answer.length; i++) {
                            var inventory = answer[i];
                            var itemid = inventory.itemid;
                            var itemcode = inventory.itemcode;
                            var tdate = inventory.tdate;
                            var details = inventory.details;
                            var qty = Number(inventory.qty);
                            var priority = inventory.priority;
                            // alert(details);

                            if (ctr == 0){
                                prev_itemid = inventory.itemid;
                                prev_code = inventory.itemcode;
                            }

                            ctr = ctr + 1;

                            curr_itemid = inventory.itemid;        
                            curr_code = inventory.itemcode;
                            
                            if (prev_itemid == curr_itemid){      
                                var pdesc = inventory.pdesc;
                                var meas1 = inventory.meas1.toUpperCase();
                                var eqnum = inventory.eqnum;
                                var meas2 = inventory.meas2.toUpperCase();
                                var ucost = numberWithCommas(inventory.ucost);
                                var reorder = inventory.reorder;

                                if (details == "Inventory"){
                                    isInventory = 1;
                                }
                                    
                                switch (details) {
                                    case "Inventory":
                                        onhand = qty;
                                        break;
                                    case "Incoming":
                                        onhand = onhand + qty;
                                        break; 
                                    case "Return":
                                        onhand = onhand + qty;
                                        break;	
                                    default: 
                                        onhand = onhand - qty;
                                } 
                            }else{
                                // if (ctr != num_rec){
                                    item_num += 1;
                                    html.push('<tr>');
                                        html.push('<td style="text-align:left;border-left:1px solid white;font-size:1.2em;padding-top:4px;padding-bottom:4px;">'+item_num+'</td>');
                                        html.push('<td style="text-align:left;border-left:1px solid white;border-right:1px solid white;font-size:1.2em;padding-top:4px;padding-bottom:4px;">'+pdesc+'</td>');
                                        html.push('<td style="text-align:left;font-size:1.2em;padding-top:4px;padding-bottom:4px;">'+meas1+'</td>');
                                        html.push('<td style="text-align:center;font-size:1.2em;padding-top:4px;padding-bottom:4px;">'+eqnum+'</td>');
                                        html.push('<td style="text-align:left;font-size:1.2em;padding-top:4px;padding-bottom:4px;">'+meas2+'</td>');
                                        html.push('<td style="text-align:right;font-size:1.2em;padding-top:4px;padding-bottom:4px;">'+ucost+'</td>');
                                        html.push('<td style="text-align:right;font-size:1.2em;padding-top:4px;padding-bottom:4px;">'+reorder+'</td>');
                                        if (onhand > 0){
                                            html.push('<td style="text-align:right;border-left:1px solid white;border-right:1px solid white;font-size:1.2em;color:lightgreen;font-weight:bold;padding-top:4px;padding-bottom:4px;">'+onhand+'</td>');
                                        }else{
                                            html.push('<td style="text-align:right;border-left:1px solid white;border-right:1px solid white;font-size:1.2em;color:#ff94bd;font-weight:bold;padding-top:4px;padding-bottom:4px;">'+onhand+'</td>');
                                        }
                                        html.push('<td style="border-right:1px solid white;padding-top:4px;padding-bottom:4px;"><button type="button" class="btn btn-outline btn-sm bg-orange-400 border-orange-400 text-orange-400 btn-icon rounded-round border-2 ml-2 btnStockcard" itemid="'+prev_itemid+'" data-toggle="modal" data-target="#stockcard"><i class="icon-stack-text"></i></button></td>');
                                    html.push('</tr>');
                                // }else{
                                //     alert("bottom");
                                //     html.push('<tr>');
                                //         html.push('<td style="text-align:left;border-left:1px solid white;border-right:1px solid white;font-size:1.2em;border-bottom:1px solid white;">'+pdesc+'</td>');
                                //         html.push('<td style="text-align:left;font-size:1.2em;">'+meas1+'</td>');
                                //         html.push('<td style="text-align:center;font-size:1.2em;">'+eqnum+'</td>');
                                //         html.push('<td style="text-align:left;font-size:1.2em;">'+meas2+'</td>');
                                //         html.push('<td style="text-align:right;font-size:1.2em;">'+ucost+'</td>');
                                //         html.push('<td style="text-align:right;font-size:1.2em;">'+reorder+'</td>');
                                //         if (onhand > 0){
                                //             html.push('<td style="text-align:right;border-left:1px solid white;border-right:1px solid white;font-size:1.2em;color:lightgreen;font-weight:bold;">'+onhand+'</td>');
                                //         }else{
                                //             html.push('<td style="text-align:right;border-left:1px solid white;border-right:1px solid white;font-size:1.2em;color:#ff94bd;font-weight:bold;">'+onhand+'</td>');
                                //         }
                                //         html.push('<td style="border-right:1px solid white;"><button type="button" class="btn btn-outline btn-sm bg-orange-400 border-orange-400 text-orange-400 btn-icon rounded-round border-2 ml-2 btnStockcard" itemid="'+prev_itemid+'" data-toggle="modal" data-target="#stockcard"><i class="icon-stack-text"></i></button></td>');
                                //     html.push('</tr>');                                    
                                // }

                                prev_itemid = curr_itemid;
                                prev_code = curr_code;

                                pdesc = inventory.pdesc;

                                onhand = 0.00;
                                isInventory = 0;
                                if (details == "Inventory"){
                                    isInventory = 1;
                                }

                                switch (details) {
                                    case "Inventory":
                                        onhand = qty;
                                        break;
                                    case "Incoming":
                                        onhand = onhand + qty;
                                        break;   
                                    case "Return":
                                        onhand = onhand + qty;
                                        break;		
                                    default:  
                                        onhand = onhand - qty;
                                }
                            }
                            
                        }
                    html.push('</table>');
                html.push('</div>');
                $('.inventory_content').html(html.join(''));
            }
        });    
    }

    $(document).on('click', '.btnStockcard', function() {
        var itemid = $(this).attr('itemid');
        let asof_date = $("#date-asofdate").val();
        let asofdate = asof_date.substring(6, 10) + '-' + asof_date.substring(0, 2) + '-' + asof_date.substring(3, 5);

        var data = new FormData();                     
        data.append("itemid", itemid);
        data.append("asofdate", asofdate);
        $.ajax({
            url:"ajax/stockcard.ajax.php",   
            method: "POST",                
            data: data,                    
            cache: false,                  
            contentType: false,            
            processData: false,            
            dataType:"json",               
            success:function(answer){
                $(".stockcard_content").empty();
                var html = [];
                var isInventory = 0;
                var prev_qty = 0.00;
                var onhand = 0.00;
                var txt_onhand = '';
                var month_name = '';
                var upper_desc = '';

                var ctr = 0;
                var interval = 0;      

                // html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 500px;">');
                    // html.push('<table class="ble datatable-basic table-bordered table-hover datatable-small-font profile-grid-header">');
                    html.push('<thead>');
                        html.push('<tr>');
                        html.push('<th style="width:150px;">Routine</th>');
                        html.push('<th style="width:135px;">Code</th>');
                        html.push('<th style="width:187px;">Date</th>');
                        html.push('<th style="width:290px;">Stakeholder</th>');
                        html.push('<th style="width:110px;text-align:right;">IN ( + )</th>');
                        html.push('<th style="width:110px;text-align:right;">OUT ( - )</th>');
                        html.push('<th style="width:110px;text-align:right;">In-Stock</th>');
                        html.push('</tr>');
                    html.push('</thead>');

                    for(var i = 0; i < answer.length; i++) {
                        var stockcard = answer[i];
                        var details = stockcard.details;
                        var tcode = stockcard.tcode;

                        var transinfo = stockcard.transinfo;

                        var trans_date = stockcard.tdate;
                        var day_num = Number(trans_date.substring(8, 10));
                        var day_str = day_num.toString();
                        var month_num = trans_date.substring(5, 7);

                        // var onhand_whole = 0.00;
                        // var txt_onhand_whole = ''
                                        
                        switch(month_num){
                        case "01":
                                    month_name = "January";
                                    break;
                        case "02":
                                    month_name = "February";
                                    break;
                        case "03":
                                    month_name = "March";
                                    break;
                        case "04":
                                    month_name = "April";
                                    break;
                        case "05":
                                    month_name = "May";
                                    break;
                        case "06":
                                    month_name = "June";
                                    break;
                        case "07":
                                    month_name = "July";
                                    break;
                        case "08":
                                    month_name = "August";
                                    break;
                        case "09":
                                    month_name = "September";
                                    break;
                        case "10":
                                    month_name = "October";
                                    break;
                        case "11":
                                    month_name = "November";
                                    break;      
                                default:
                                    month_name = "December";                                                                                                                
                        }

                        var tdate = month_name + ' ' + day_str + ', ' + trans_date.substring(0, 4);
                        var priority = stockcard.priority;
                        var eqnum = stockcard.eqnum;
                        var prod_qty = stockcard.qty;           
                        var qty = Number(prod_qty);
                        // var txt_qty = formatNumber(qty.toFixed(0));
                        if (eqnum == 1.00){
                        var pdesc = stockcard.pdesc + ' (' + stockcard.meas1 + ')';
                        }else{
                        var pdesc = stockcard.pdesc + ' (' + stockcard.meas1 + ') => ' + eqnum + ' (' + stockcard.meas2 + ')';
                        }  
                        upper_pdesc = pdesc.toUpperCase();
                        $("#product_name").html(upper_pdesc);

                        ctr = ctr + 1;
                        if (ctr == 1){
                        interval = 1;
                        prev_date = tdate;
                        }
                        curr_date = tdate;

                        if (prev_date !== curr_date){
                        interval = interval + 1;
                        prev_date = tdate;
                        }          

                        if (details == "Inventory"){
                            isInventory = 1;
                        }

                        // alert(details + ' ' + tcode + ' ' + transinfo + ' ' + trans_date + ' ' + tdate + ' ' + qty + ' ' + upper_pdesc);

                        // if (isInventory == 1){
                            if (details == "Inventory"){
                                onhand = qty;
                                // txt_onhand = formatNumber(onhand.toFixed(0));                       
                                // html.push('<tr>');
                                // if (interval % 2 != 0){
                                //     html.push('<tr style="background-color:#212121;">');
                                // }else{
                                    html.push('<tr>');
                                // }              
                                html.push('<td style="text-align:left;">'+details+'</td>');
                                html.push('<td style="text-align:left;color:orange;">'+tcode+'</td>');
                                // html.push('<td style="text-align:left;color:orange;"></td>'); 
                                html.push('<td style="text-align:left;">'+tdate+'</td>');
                                html.push('<td style="text-align:left;">'+transinfo+'</td>');
                                // html.push('<td style="text-align:left;"></td>');
                                html.push('<td style="text-align:right;"></td>');
                                html.push('<td style="text-align:right;"></td>');
                                html.push('<td style="text-align:right;">'+onhand+'</td>');
                                html.push('</tr>');
                                prev_qty = qty;
                            }

                            if ((details == "Incoming")||(details == "Return")){
                                onhand = onhand + qty;  
                                // txt_onhand = formatNumber(onhand.toFixed(0));                   
                                // html.push('<tr>');
                                // if (interval % 2 != 0){
                                //     html.push('<tr style="background-color:#212121;">');
                                // }else{
                                    html.push('<tr>');
                                // }               
                                html.push('<td style="text-align:left;">'+details+'</td>');
                                html.push('<td style="text-align:left;color:orange;">'+tcode+'</td>');
                                // html.push('<td style="text-align:left;color:orange;"></td>');
                                html.push('<td style="text-align:left;">'+tdate+'</td>');
                                html.push('<td style="text-align:left;">'+transinfo+'</td>');
                                // html.push('<td style="text-align:left;"></td>');
                                html.push('<td style="text-align:right;color:lightgreen;">'+qty+'</td>');
                                html.push('<td style="text-align:right;"></td>');
                                html.push('<td style="text-align:right;">'+onhand+'</td>');
                                html.push('</tr>');
                                prev_qty = qty;
                            }   

                            if (details == "Withdrawal"){
                                onhand = onhand - qty;
                                // txt_onhand = formatNumber(onhand.toFixed(0));                       
                                // html.push('<tr>');
                                // if (interval % 2 != 0){
                                //     html.push('<tr style="background-color:#212121;">');
                                // }else{
                                    html.push('<tr>');
                                // }               
                                html.push('<td style="text-align:left;">'+details+'</td>');
                                html.push('<td style="text-align:left;color:orange;">'+tcode+'</td>');
                                html.push('<td style="text-align:left;">'+tdate+'</td>');
                                html.push('<td style="text-align:left;">'+transinfo+'</td>');
                                html.push('<td style="text-align:right;"></td>');
                                html.push('<td style="text-align:right;color:lightsalmon;">'+qty+'</td>');
                                html.push('<td style="text-align:right;">'+onhand+'</td>');
                                html.push('</tr>');
                                prev_qty = qty;
                            }                       

                        // }   if (isInventory == 1){
                    }
                                        
                    // html.push('</table>');
                // html.push('</div>'); 

                $('.stockcard_content').html(html.join(''));  
            }
        });
    });

    $("#btn-print-inventory").click(function(){
        let categorycode = $("#sel-categorycode").val(); 
        let asof_date = $("#date-asofdate").val();
        let asofdate = asof_date.substring(6, 10) + '-' + asof_date.substring(0, 2) + '-' + asof_date.substring(3, 5);

        let generatedby = $("#txt-generatedby").val();
        window.open("extensions/tcpdf/pdf/inventory_report.php?categorycode="+categorycode+"&asofdate="+asofdate+"&generatedby="+generatedby, "_blank");
    });
    
    $("#btn-reset-inventory").click(function(){
        $("#sel-categorycode").val('').trigger('change');
        $("#tns-search").val('');

        var currentDate = new Date();
        var formattedDate = (currentDate.getMonth() + 1).toString().padStart(2, '0') + '/' +
                            currentDate.getDate().toString().padStart(2, '0') + '/' +
                            currentDate.getFullYear();
        $('#date-asofdate').val(formattedDate);
        generateInventory();
    });
    
    function filterItemInventoryTable() {
        let keyword = $('#tns-search').val().toLowerCase().trim();

        $('.itemInventoryTable tbody tr').each(function() {
            let productDesc = $(this).find('td:nth-child(2)').text().toLowerCase();

            if (productDesc.indexOf(keyword) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    // Bind the function to keyup event
    $('#tns-search').on('keyup', filterItemInventoryTable);    

    $('#lbl-tns-search').on('click', function () {
        $('#tns-search').val('');
        filterItemInventoryTable();
    });       
    
    function numberWithCommasNoDecimal(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
});