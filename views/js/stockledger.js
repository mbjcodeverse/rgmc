if (!$.fn.DataTable.isDataTable('.inventoryPeriodsTable')) {
    var ip = $('.inventoryPeriodsTable').DataTable({
        processing: true,
        autoWidth: true,
        scrollY: '55vh',
        keys: true,
        ordering: false,
        paging: false,
        dom: '<"datatable-header"f><"datatable-scroll"t><"datatable-footer"i>',
        language: {
            loadingRecords: 'Loading inventory periods...',
            search: '<span>Filter:</span> _INPUT_',
            searchPlaceholder: 'Type to filter...',
            lengthMenu: '<span>Show:</span> _MENU_',
            info: "Showing _START_ to _END_ of _TOTAL_ records",
            infoEmpty: "No records available",
            paginate: { 
                first: 'First', 
                last: 'Last', 
                next: $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 
                previous: $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' 
            }
        }
    });
}

$(function() {
    var today = new Date();
    var date_today = (today.getMonth() + 1).toString().padStart(2, '0') + '/' +
                      today.getDate().toString().padStart(2, '0') + '/' +
                      today.getFullYear();
    $("#date-ldate").val(date_today);

    $("#btn-copy").prop('disabled', true);
    $("#btn-export").prop('disabled', true);
    $("#btn-print").prop('disabled', true);

    var previousDate = $('#date-ldate').val();      // Initialize with current date value

    $('#date-ldate').on('change', function() {
        var currentDate = $('#date-ldate').val();   // Get the current value of the date input
        // Check if the date has actually changed
        if (currentDate !== previousDate) {
            $(".stock_ledger").empty();
            $('#btn-period').click();               // Only click the button if the date has changed
            previousDate = currentDate;             // Update the previousDate with the new value
        }
    });

    $('#btn-period').on('click', function() {
        $('#modal-inventory-periods').modal('show'); 
        load_inventory_periods();	
    });   

    function load_inventory_periods() {
        $.ajax({
            url: "ajax/stock_periods.ajax.php",
            method: "POST",
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(answer) {
                $(".inventoryPeriodsTable").DataTable().clear();
                var ip = $(".inventoryPeriodsTable").DataTable(); // ensure `ip` is defined here if it's not globally

                let inv_end = ""; // store last inv_end and button
                let button = "";

                for (var i = 0; i < answer.length; i++) {
                    let period = answer[i];

                    // From
                    var inventoryfrom = period.inventoryfrom;
                    let inv_from = period.inventoryfrom;
                    let date_Obj = new Date(inv_from);
                    let inv_start = date_Obj.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

                    // Date From: 01/01/2025
                    let part1 = inventoryfrom.split("-");
                    var _inv_from = part1[1] + "/" + part1[2] + "/" + part1[0];

                    let invnumber_from = period.invnumber_from;
                    var inventoryfromnextday = period.inventoryfromnextday;

                    // To
                    var inventoryto = period.inventoryto;
                    let inv_to = period.inventoryto;
                    let dateObj = new Date(inv_to);
                    inv_end = dateObj.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

                    // Date To: 01/01/2025
                    let part2 = inventoryto.split("-");
                    var _inv_to = part2[1] + "/" + part2[2] + "/" + part2[0];

                    let invnumber_to = period.invnumber_to;
                    var inventorytonextday = period.inventorytonextday;

                    button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnInvPeriod' inventoryfrom='" + inventoryfrom + "' inventoryfromnextday='" + inventoryfromnextday + "' _inv_from='" + _inv_from + "' inventoryto='" + inventoryto + "' inventorytonextday='" + inventorytonextday + "' _inv_to='" + _inv_to + "'><i class='icon-check'></i></button></td>";
                    
                    if (inv_start != inv_end){
                        ip.row.add([inv_start, inv_end, button]);
                    }
                }

                // Add the final row (formattedDate) after loop
                let l_date = $("#date-ldate").val();
                if (l_date != '') {
                    // Current Selected Date: Year-Month-day
                    let part3 = l_date.split("/");
                    var inventory_latest = part3[2] + "-" + part3[0] + "-" + part3[1];

                    // June 3, 2025
                    let [m, d, y] = l_date.split("/");
                    let formattedDate = new Date(`${y}-${m}-${d}`).toLocaleDateString('en-US', {
                        year: 'numeric', month: 'long', day: 'numeric'
                    });
                    button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnInvPeriod' inventoryfrom='" + inventoryto + "' inventoryfromnextday='" + inventorytonextday + "' _inv_from='" + _inv_to + "' inventoryto='" + inventory_latest + "' inventorytonextday='" + inventorytonextday + "' _inv_to='" + l_date + "'><i class='icon-check'></i></button></td>";
                    ip.row.add([inv_end, formattedDate, button]);
                }

                ip.draw();
                $('.inventoryPeriodsTable tbody tr:last-child').attr('style', 'color: #11faac;');
            }
        });
    }

    // Selected Inventory Period (From - To)
    $(".inventoryPeriodsTable tbody").on('click', '.btnInvPeriod', function () {
        $(".stock_ledger").empty();

        $("#btn-copy").prop('disabled', false);
        $("#btn-export").prop('disabled', false);
        $("#btn-print").prop('disabled', false);

        let inventoryfrom = $(this).attr("inventoryfrom");
        let _inv_from = $(this).attr("_inv_from");
        let inventoryfromnextday = $(this).attr("inventoryfromnextday");

        let inventoryto = $(this).attr("inventoryto");
        let inventorytonextday = $(this).attr("inventorytonextday");
        let _inv_to = $(this).attr("_inv_to");

        $("#date-invfrom").val(_inv_from);
        $("#date-invto").val(_inv_to);

        $("#inventoryfrom").val(inventoryfrom);
        $("#inventoryfromnextday").val(inventoryfromnextday);

        $("#inventoryto").val(inventoryto);
        $("#inventorytonextday").val(inventorytonextday);

        $("#modal-inventory-periods").modal('hide');

        // alert(inventoryfrom + ' ' + inventoryfromnextday + ' ' + inventoryto);

        var stock_period = new FormData();
        stock_period.append("inventoryfrom", inventoryfrom);
        stock_period.append("inventoryfromnextday", inventoryfromnextday);
        stock_period.append("inventoryto", inventoryto);
        $.ajax({
            url:"ajax/stock_matrix.ajax.php",
            method: "POST",
            data: stock_period,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(answer){
                var html = [];
                html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;margin-bottom:12px;">');
                // html.push('<div class="table-responsive" style="margin-top:25px;margin-bottom:-28px;margin-left:18px;margin-right:18px;overflow-y: auto; overflow-x: auto; max-height: 500px;">');
                    html.push('<table class="table mx-auto w-auto productInventoryTable" style="border-collapse: separate; border-spacing: 0;">');
                        html.push('<thead>');
                            html.push('<tr>');
                                html.push('<th class="table_head_left_fixed" style="position: sticky; left: 0; z-index: 10; background-color: #1f1f1f; padding-top:8px; padding-bottom:8px; min-width:350px;font-size:1.1em;">ITEM DESCRIPTION</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-right:11px;padding-top:8px;padding-bottom:8px;min-width:75px;color:#89fa91;">Beginning</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-right:11px;padding-top:8px;padding-bottom:8px;min-width:75px;color:#89fa91;">Purchase</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-right:11px;padding-top:8px;padding-bottom:8px;min-width:75px;color:#89fa91;">Return</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-right:11px;padding-top:8px;padding-bottom:8px;min-width:90px;color:#7FFF00;">INBOUND</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-right:11px;padding-top:8px;padding-bottom:8px;min-width:75px;color:#ffd6ad;">RELEASE</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-right:11px;padding-top:8px;padding-bottom:8px;min-width:90px;border-right:3px solid white;">STOCK</th>');

                                if ($("#date-invto").val() != $("#date-ldate").val()){
                                    html.push('<th class="table_head_right_fixed" style="padding-right:11px;padding-top:8px;padding-bottom:8px;min-width:75px;color:#89fa91;">Ending</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-right:11px;padding-top:8px;padding-bottom:8px;min-width:90px;border-right:3px solid white;">VARIANCE</th>');
                                }

                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Act</th>');
                            html.push('</tr>');
                        html.push('</thead>');

                        for(var i = 0; i < answer.length; i++) {
                            let matrix = answer[i];
                            let pdesc = matrix.product_display_name;
                            let itemid = matrix.itemid;
                            let beginning_qty = parseInt(matrix.beginning_qty);
                            let beginning_tamount = Number(matrix.beginning_tamount);
                            let purchase_qty_total = parseFloat(matrix.purchase_qty_total);
                            let purchase_tamount_total = Number(matrix.purchase_tamount_total);
                            let return_qty_total = parseFloat(matrix.return_qty_total);
                            let inbound = beginning_qty + purchase_qty_total + return_qty_total;
                            let release_qty_total = parseFloat(matrix.release_qty_total);
                            let release_tamount_total = Number(matrix.release_tamount_total);
                            let instock = inbound - release_qty_total;
                            let ending_qty = parseInt(matrix.ending_qty);
                            let ending_tamount = Number(matrix.ending_tamount);
                            let variance = ending_qty - instock;

                            // Check if pdesc exceeds 35 characters and truncate if necessary
                            if (pdesc.length > 35) {
                                pdesc = pdesc.substring(0, 35) + ' . . .';
                            }

                            html.push('<tr>');         
                                html.push('<td style="padding-top:4px;padding-bottom:4px;position: sticky; left: 0; z-index: 5; background-color: #1f1f1f; border-right: 1px solid white; font-size:1.1em; color:lightyellow;">' + pdesc + '</td>');
                                if (beginning_qty != 0){
                                    html.push('<td style="padding-right:11px;padding-top:4px;padding-bottom:4px;text-align:right;border-right:1px solid white;border-bottom:1px solid white;color:#89fa91;">'+beginning_qty+'</td>');
                                    // html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(beginning_tamount)+'</td>');
                                }else{
                                    html.push('<td style="padding-right:11px;padding-top:4px;padding-bottom:4px;text-align:right;border-right:1px solid white;border-bottom:1px solid white;"></td>');
                                    // html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                }

                                if (purchase_qty_total != 0){
                                    html.push('<td style="padding-right:11px;padding-top:4px;padding-bottom:4px;text-align:right;border-right:1px solid white;border-bottom:1px solid white;color:#89fa91;">'+purchase_qty_total+'</td>');
                                    // html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(purchase_tamount_total)+'</td>');
                                }else{
                                    html.push('<td style="padding-right:11px;padding-top:4px;padding-bottom:4px;text-align:right;border-right:1px solid white;border-bottom:1px solid white;"></td>');
                                    // html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                }

                                if (return_qty_total != 0){
                                    html.push('<td style="padding-right:11px;padding-top:4px;padding-bottom:4px;text-align:right;border-right:1px solid white;border-bottom:1px solid white;color:#89fa91;">'+return_qty_total+'</td>');
                                }else{
                                    html.push('<td style="padding-right:11px;padding-top:4px;padding-bottom:4px;text-align:right;border-right:1px solid white;border-bottom:1px solid white;"></td>');
                                }

                                // TOTAL STOCK-IN
                                if (inbound != 0){
                                    html.push('<td style="padding-right:11px;padding-top:4px;padding-bottom:4px;text-align:right;border-right:1px solid white;border-bottom:1px solid white;font-size:1.1em;font-weight:bold;color:#7FFF00;">'+numberWithCommasNoDecimal(inbound)+'</td>');
                                }else{
                                    html.push('<td style="padding-right:11px;padding-top:4px;padding-bottom:4px;text-align:right;border-right:1px solid white;border-bottom:1px solid white;font-size:1.1em;font-weight:bold;color:#7FFF00;"></td>');
                                }

                                if (release_qty_total != 0){
                                    html.push('<td style="padding-right:11px;padding-top:4px;padding-bottom:4px;text-align:right;border-right:1px solid white;border-bottom:1px solid white;color:#89fa91;">'+release_qty_total+'</td>');
                                    // html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(purchase_tamount_total)+'</td>');
                                }else{
                                    html.push('<td style="padding-right:11px;padding-top:4px;padding-bottom:4px;text-align:right;border-right:1px solid white;border-bottom:1px solid white;"></td>');
                                    // html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                }

                                if (instock > 0){
                                    html.push('<td style="padding-right:11px;padding-top:2px;padding-bottom:2px;text-align:right;border:1px solid white;font-size:1.1em;font-weight:bold;color:white;background-color:#117844;">'+numberWithCommasNoDecimal(instock)+'</td>');
                                }else if (instock < 0){
                                    html.push('<td style="padding-right:11px;padding-top:2px;padding-bottom:2px;text-align:right;border:1px solid white;font-size:1.1em;font-weight:bold;color:white;background-color:red;">'+numberWithCommasNoDecimal(instock)+'</td>');
                                }else{
                                    html.push('<td style="padding-right:11px;padding-top:2px;padding-bottom:2px;text-align:right;border:1px solid white;font-size:1.1em;font-weight:bold;color:white;"></td>');
                                }

                                if ($("#date-invto").val() != $("#date-ldate").val()){
                                    if (ending_qty != 0){
                                        html.push('<td style="padding-right:11px;padding-top:4px;padding-bottom:4px;text-align:right;border-right:1px solid white;border-bottom:1px solid white;color:#89fa91;">'+ending_qty+'</td>');
                                        // html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(beginning_tamount)+'</td>');
                                    }else{
                                        html.push('<td style="padding-right:11px;padding-top:4px;padding-bottom:4px;text-align:right;border-right:1px solid white;border-bottom:1px solid white;"></td>');
                                        // html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                    }

                                    if (variance > 0){
                                        html.push('<td style="padding-right:11px;padding-top:2px;padding-bottom:2px;text-align:right;border:1px solid white;font-size:1.1em;font-weight:bold;color:white;background-color:#117844;">'+numberWithCommasNoDecimal(variance)+'</td>');
                                    }else if (variance < 0){
                                        html.push('<td style="padding-right:11px;padding-top:2px;padding-bottom:2px;text-align:right;border:1px solid white;font-size:1.1em;font-weight:bold;color:white;background-color:red;">'+numberWithCommasNoDecimal(variance)+'</td>');
                                    }else{
                                        html.push('<td style="padding-right:11px;padding-top:2px;padding-bottom:2px;text-align:right;border:1px solid white;font-size:1.1em;font-weight:bold;color:white;"></td>');
                                    }
                                }
                                
                                if (beginning_qty != 0 || purchase_qty_total != 0 || return_qty_total != 0 || release_qty_total != 0){
                                    html.push('<td style="border-right:1px solid white;border-bottom:1px solid white;border-top:1px solid white;padding-top:4px;padding-bottom:4px;"><button type="button" class="btn btn-outline btn-sm bg-orange-400 border-orange-400 text-orange-400 btn-icon rounded-round border-2 ml-2 btnStockcardPeriod" itemid="'+itemid+'" data-toggle="modal" data-target="#stockcard"><i class="icon-stack-text"></i></button></td>');
                                }
                            html.push('</tr>');
                        }
                    html.push('</table>');
                html.push('</div>'); 

                $('.stock_ledger').html(html.join(''));
                // $('#date-invto').prop('disabled', false);
                // $('#tns-search').prop('disabled', false);
                // $('#tns-search').focus();
            }
        });
    });

    $(document).on('click', '.btnStockcardPeriod', function() {
        var itemid = $(this).attr('itemid');
        let asof_date = $("#date-ldate").val();
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

    $("#btn-template").click(function(){
        let date_start = $("#date-invfrom").val();
        let start_date = date_start.substring(6, 10) + '-' + date_start.substring(0, 2) + '-' + date_start.substring(3, 5);

        let date_from = $("#inventoryfromnextday").val();
        let from_date = date_from.substring(6, 10) + '-' + date_from.substring(0, 2) + '-' + date_from.substring(3, 5);

        let date_end = $("#date-invto").val();
        let end_date = date_end.substring(6, 10) + '-' + date_end.substring(0, 2) + '-' + date_end.substring(3, 5);
        
        // alert(start_date + "     " + end_date);
        // var branchcode = $('#lst-branchcode').val();
        // let t_date = $("#date-tdate").val();
        // let tdate = t_date.substring(6, 10) + '-' + t_date.substring(0, 2) + '-' + t_date.substring(3, 5);
        // let reptype = $("#lst-reptype").val();

        let generatedby = $("#txt-generatedby").val();
        window.open("extensions/tcpdf/pdf/inventory_template.php?start_date="+start_date+"&from_date="+from_date+"&end_date="+end_date+"&generatedby="+generatedby, "_blank");
    });     

    $("#btn-export").click(function(){
        exportToExcel();
    }); 
    
    $('#btn-copy').on('click', function() {
        // Get the content of the overall_assessment div
        var content = $('.stock_ledger').html();

        // Create a temporary textarea element to hold the HTML content
        var $temp = $("<textarea>");
        $("body").append($temp);
        
        // Set the content of the textarea to the table's HTML
        $temp.val(content).select();
        
        // Execute the copy command
        document.execCommand("copy");

        // Remove the temporary textarea
        $temp.remove();

        // Alert the user that the content has been copied
        // alert("Content copied to clipboard! You can now paste it into Excel.");

        swal.fire({
            title: 'Content copied to clipboard! You can now paste it into Excel.',
            type: 'success',
            confirmButtonText: 'Got it!',
            confirmButtonClass: 'btn btn-outline-success',
            allowOutsideClick: false,
            buttonsStyling: false
            }).then(function(result){
                if(result.value) {              
                    // $("#btn-new").click();
                }
        });
    });

    $("#btn-print").click(function(){

        // var branchcode = $('#lst-branchcode').val();
        // let t_date = $("#date-tdate").val();
        // let tdate = t_date.substring(6, 10) + '-' + t_date.substring(0, 2) + '-' + t_date.substring(3, 5);
        // let reptype = $("#lst-reptype").val();

        // let generatedby = $("#tns-generatedby").val();
        // window.open("extensions/tcpdf/pdf/inventory_report.php?tdate="+tdate+"&branchcode="+branchcode+"&reptype="+reptype+"&generatedby="+generatedby, "_blank");
    });  
  
    function exportToExcel() {
        var location = 'data:application/vnd.ms-excel;base64,';
        var excelTemplate = '<html> ' +
        '<head> ' +
        '<meta http-equiv="content-type" content="text/plain; charset=UTF-8"/> ' +
        '</head> ' +
        '<body> ' +
        document.getElementById("stock_ledger").innerHTML +
        '</body> ' +
        '</html>'
        window.location.href = location + window.btoa(excelTemplate);
    } 

    function numberWithCommasNoDecimal(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
});