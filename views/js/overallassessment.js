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
                    paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
                }
    });
}

if (!$.fn.DataTable.isDataTable('.incomingTable')) {
    var ipl = $('.incomingTable').DataTable({
        processing: true,
        autoWidth: true,
        scrollY: '55vh',
        keys: true,
        paging: false,
        dom: '<"datatable-header"f><"datatable-scroll"t><"datatable-footer"i>',
                language: {
                    loadingRecords: 'Loading incoming details...',
                    search: '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
                }
    });
}

if (!$.fn.DataTable.isDataTable('.creditTable')) {
    var cred_table = $('.creditTable').DataTable({
        processing: true,
        autoWidth: true,
        scrollY: '55vh',
        keys: true,
        paging: false,
        dom: '<"datatable-header"f><"datatable-scroll"t><"datatable-footer"i>',
                language: {
                    loadingRecords: 'Loading incoming details...',
                    search: '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
                }
    });
}

if (!$.fn.DataTable.isDataTable('.counterTable')) {
    var counter_table = $('.counterTable').DataTable({
        processing: true,
        autoWidth: true,
        scrollY: '55vh',
        keys: true,
        paging: false,
        dom: '<"datatable-header"f><"datatable-scroll"t><"datatable-footer"i>',
                language: {
                    loadingRecords: 'Loading incoming details...',
                    search: '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
                }
    });
}

$(function() {  
    if ($("#branch_code").val() != ''){   // Assessment Report - Outlet
       let branchcode = $("#branch_code").val();
       $('#lst-branchcode').val(branchcode).trigger('change');
       $('#lst-branchcode').unbind("change");
       $("#lst-branchcode").prop('disabled', true);
    }else{                                // Assessment Report - Central Office
       // let branchcode = $('#lst-branchcode').val();
       $('#lst-branchcode').bind("change");
       $("#lst-branchcode").prop('disabled', false);
    }   

    var today = new Date();
    var date_today = (today.getMonth() + 1).toString().padStart(2, '0') + '/' +
                      today.getDate().toString().padStart(2, '0') + '/' +
                      today.getFullYear();
    $("#date-ldate").val(date_today);

    var previousDate = $('#date-ldate').val(); // Initialize with current date value

    $('#date-ldate').on('change', function() {
        var currentDate = $('#date-ldate').val(); // Get the current value of the date input

        // Check if the date has actually changed
        if (currentDate !== previousDate) {
            $(".overall_assessment").empty();
            $('#btn-period').click(); // Only click the button if the date has changed

            previousDate = currentDate; // Update the previousDate with the new value
        }
    });

    $('#btn-period').on('click', function() {
        let branchcode = $('#lst-branchcode').val();
        if (!branchcode) {
            swal.fire({
               title: 'Cannot display, select branch!',
               type: 'error',
               allowOutsideClick: false,
               showConfirmButton: false,
               timer: 2500
            })
        } else {
            $('#modal-inventory-periods').modal('show'); 
            load_inventory_periods();	
        }
    });
 
    // $('#lst-branchcode, #date-tdate, #lst-reptype').on("change", function(){
    //    if (($("#lst-branchcode").val() != '')&&($("#date-tdate").val() != '')&&($("#lst-reptype").val() != '')){
    //         let branchcode = $('#lst-branchcode').val();
    //         let trans_date = $("#date-tdate").val();
    //         let tdate = trans_date.substring(6, 10) + '-' + trans_date.substring(0, 2) + '-' + trans_date.substring(3, 5);
    //         let reptype = $("#lst-reptype").val();

    //         $(".overall_content").empty();

    //         // Disable selectors during Inventory Tabulation
    //         $("#lst-branchcode").prop('disabled', true);
    //         $("#date-tdate").prop('disabled', true);
    //         $("#lst-reptype").prop('disabled', true);
    //         $("#btn-print-report").prop('disabled', true);
    //         $("#btn-export").prop('disabled', true);

    //         var percent = 0;
    //         var notice = new PNotify({
    //             text: "Codifying inventory..",
    //             addclass: 'stack-left-right bg-primary border-primary',
    //             type: 'info',
    //             icon: 'icon-spinner4 spinner',
    //             hide: false,
    //             buttons: {
    //                 closer: false,
    //                 sticker: false
    //             },
    //             opacity: .9,
    //             width: "190px"
    //         });      
        
    //         var data = new FormData();
    //         data.append("branchcode", branchcode);
    //         data.append("tdate", tdate);
    //         data.append("reptype", reptype);
        
    //         $.ajax({
    //                 url:"ajax/overall_report.ajax.php",   
    //                 method: "POST",                
    //                 data: data,                    
    //                 cache: false,                  
    //                 contentType: false,            
    //                 processData: false,          
    //                 dataType:"json",               
    //                 success:function(answer){
    //                 $(".overall_content").empty();
    //                 var html = [];
    //                 if (reptype == 1){
    //                     html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 500px;">');
    //                         html.push('<table class="table mx-auto w-auto productInventoryTable">');
    //                             html.push('<thead>');
    //                                 html.push('<tr>');
    //                                     html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;min-width:400px;">Product Desc</th>');
    //                                     html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:130px;">Qty</th>');
    //                                     html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:130px;">Cost</th>');
    //                                     html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:130px;">Amount</th>');
    //                                     html.push('<th class="table_head_center_fixed" style="padding-top:8px;padding-bottom:8px;min-width:20px;">SC</th>');
    //                                 html.push('</tr>');
    //                             html.push('</thead>');
                                
    //                             var prev_prodid = '';
    //                             var curr_prodid = '';
    //                             var onhand = 0.00;
    //                             var inventory_value = 0.00;
    //                             for(var i = 0; i < answer.length; i++){
    //                                 percent = Math.round(i/answer.length*100);
    //                                 var options = {
    //                                     text: percent + "% complete."
    //                                 };

    //                                 var inv = answer[i];
    //                                 var qty = Number(inv.qty);
    //                                 var priority = inv.priority;
    //                                 var details = inv.details;

    //                                 if (i == 0){
    //                                     prev_prodid = inv.prodid;           
    //                                 }

    //                                 curr_prodid = inv.prodid;               
    //                                 if (curr_prodid == prev_prodid){        
    //                                     var prodname = inv.prodname; 
    //                                     var ucost = Number(inv.ucost);
    //                                     if (details == 'Inventory'){
    //                                         onhand = qty;
    //                                     }else if ((details == 'Incoming')||(details == 'Repack-In')||(details == 'Adjustment-Add')){
    //                                         onhand = onhand + qty;
    //                                     }else{
    //                                         onhand = onhand - qty;
    //                                     }
    //                                     // console.log(prodname + ' : ' + onhand);
    //                                 }else{
    //                                     if ((ucost * onhand) > 0.00){   // do not add to inventory value negative qty & amount
    //                                         inventory_value = inventory_value + (ucost * onhand);
    //                                     }

    //                                     html.push('<tr>');                                       
    //                                         html.push('<td style="border-right:1px solid white;">'+prodname+'</td>');
    //                                         html.push('<td style="text-align:right;">'+numberWithCommas(onhand)+'</td>');
    //                                         html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(ucost)+'</td>');
    //                                         html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(ucost * onhand)+'</td>');
    //                                         html.push('<td><button style="z-index:2;" type="button" class="btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnStockCard" prodname="'+prodname+'" prodid="'+prev_prodid+'" data-toggle="modal" data-target="#stockcard"><i class="icon-book"></i></button></td>');
    //                                     html.push('</tr>');

    //                                     prev_prodid = curr_prodid;                               
    //                                     prodname = inv.prodname;                                 
    //                                     ucost = inv.ucost;
    //                                     onhand = 0.00;
    //                                     if (details == 'Inventory'){
    //                                         onhand = qty;
    //                                     }else if ((details == 'Incoming')||(details == 'Repack-In')||(details == 'Adjustment-Add')){
    //                                         onhand = onhand + qty;
    //                                     }else{
    //                                         onhand = onhand - qty;
    //                                     }
    //                                 }
    //                             }

    //                             notice.update(options);
    //                             notice.remove();

    //                             if (i > 0){
    //                                 inventory_value = inventory_value + (ucost * qty);
    //                                 html.push('<tr>');                                       
    //                                     html.push('<td style="text-align:left;border-bottom:2px solid white;border-right:1px solid white;">'+prodname+'</td>');
    //                                     html.push('<td style="text-align:right;border-bottom:2px solid white;">'+numberWithCommas(onhand)+'</td>');
    //                                     html.push('<td style="text-align:right;border-bottom:2px solid white;border-right:1px solid white;">'+numberWithCommas(ucost)+'</td>');
    //                                     html.push('<td style="text-align:right;border-bottom:2px solid white;border-right:1px solid white;">'+numberWithCommas(ucost * qty)+'</td>');
    //                                     html.push('<td><button style="z-index:2;" type="button" class="btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnStockCard" prodname="'+prodname+'"><i class="icon-book"></i></button></td>');
    //                                 html.push('</tr>');
    //                                 html.push('<tr>');                                       
    //                                     html.push('<td colspan="3" style="text-align:right;font-size:1.3em;border-right:1px solid white;border-bottom:2px solid white;">CURRENT STORE VALUE</td>');
    //                                     html.push('<td style="text-align:right;border-bottom:2px solid white;font-size:1.3em;border-right:1px solid white;">'+numberWithCommas(inventory_value)+'</td>');
    //                                 html.push('</tr>');
    //                             }
    //                         html.push('</table>');
    //                     html.push('</div>');

    //                     // Enable selectors after Inventory Tabulation
    //                     $("#lst-branchcode").prop('disabled', false);
    //                     $("#date-tdate").prop('disabled', false);
    //                     $("#lst-reptype").prop('disabled', false);
    //                     $("#btn-print-report").prop('disabled', false);
    //                     $("#btn-export").prop('disabled', false);

    //                 }else if (reptype == 2){              
    //                 }
    //                 $('.overall_content').html(html.join(''));  
    //                 }
    //         })  // end of Ajax  
    //    }
    // });    

    // $(".overall-report-form").on("click", "button.btnStockCard", function(){
    //     var prodname = $(this).attr("prodname");
    //     var upper_prodname = prodname.toUpperCase();
    //     $("#product_name").html(upper_prodname);

    //     var prodid = $(this).attr("prodid");
    //     var branchcode = $('#lst-branchcode').val();   
    //     var trans_date = $("#date-tdate").val();
    //     var tdate = trans_date.substring(6, 10) + '-' + trans_date.substring(0, 2) + '-' + trans_date.substring(3, 5);
    //     var data = new FormData();                     
    //     data.append("prodid", prodid);
    //     data.append("branchcode", branchcode);
    //     data.append("tdate", tdate);
        
    //     $.ajax({
    //         url:"ajax/stock_card.ajax.php",   
    //         method: "POST",                
    //         data: data,                    
    //         cache: false,                  
    //         contentType: false,            
    //         processData: false,            
    //         dataType:"json",               
    //         success:function(answer){
    //             $(".stockcard_content").empty();
    //             var html = [];
    //             html.push('<thead>');
    //                 html.push('<tr>');
    //                     html.push('<th class="table_head_left_fixed" style="width:90px;padding-left:10px;">Routine</th>');
    //                     html.push('<th class="table_head_left_fixed" style="width:105px;padding-left:10px;">Code</th>');
    //                     html.push('<th class="table_head_left_fixed" style="width:75px;padding-left:10px;">Date</th>');
    //                     html.push('<th class="table_head_left_fixed" style="width:220px;padding-left:10px;">Stakeholder</th>');
    //                     html.push('<th class="table_head_right_fixed" style="width:65px;padding-right:10px;text-align:right;">IN(+)</th>');
    //                     html.push('<th class="table_head_right_fixed" style="width:65px;padding-right:10px;text-align:right;">Cost</th>');
    //                     html.push('<th class="table_head_right_fixed" style="width:80px;padding-right:10px;text-align:right;">Amount</th>');
    //                     html.push('<th class="table_head_right_fixed" style="width:65px;padding-right:10px;text-align:right;">OUT(-)</th>');
    //                     html.push('<th class="table_head_right_fixed" style="width:65px;padding-right:10px;text-align:right;">Cost</th>');
    //                     html.push('<th class="table_head_right_fixed" style="width:80px;padding-right:10px;text-align:right;">Amount</th>');
    //                     html.push('<th class="table_head_right_fixed" style="width:70px;padding-right:10px;text-align:right;">Stock</th>');
    //                     html.push('<th class="table_head_right_fixed" style="width:65px;padding-right:10px;text-align:right;">Cost</th>');
    //                     html.push('<th class="table_head_right_fixed" style="width:85px;padding-right:10px;text-align:right;">Value</th>');
    //                 html.push('</tr>');
    //             html.push('</thead>');

    //             var isInventory = 0;
    //             var prev_qty = 0.00;
    //             var ave_cost = 0.00;
    //             var prev_value = 0.00;
    //             var total_value = 0.00;
    //             var onhand = 0.00;
    //             var txt_onhand = '';
      
    //             var ctr = 0;
    //             var interval = 0;  

    //             for(var i = 0; i < answer.length; i++) {
    //                 var stockcard = answer[i];
    //                 var details = stockcard.details;
    //                 var tcode = stockcard.tcode;
    //                 var transinfo = stockcard.transinfo;
    //                 var status = stockcard.status;

    //                 var prod_tdate = stockcard.tdate;
    //                 var tdate = prod_tdate.substring(5, 7) + '/' + prod_tdate.substring(8, 10) + '/' + prod_tdate.substring(0, 4);

    //                 var prod_qty = stockcard.qty;           
    //                 var qty = Number(prod_qty);

    //                 var prod_ucost = stockcard.ucost; 
    //                 var ucost = Number(prod_ucost);

    //                 ctr = ctr + 1;
    //                 if (ctr == 1){
    //                     interval = 1;
    //                     prev_date = tdate;
    //                 }
    //                 curr_date = tdate;
    
    //                 if (prev_date !== curr_date){
    //                     interval = interval + 1;
    //                     prev_date = tdate;
    //                 }          
    
    //                 if (details == "Inventory"){
    //                     isInventory = 1;
    //                 }

    //                 if (details == "Inventory"){
    //                     onhand = qty;  
    //                     if (ave_cost == 0.00){
    //                         ave_cost = ucost;
    //                     }
    //                     total_value = ave_cost * onhand;
    //                     html.push('<tr>');              
    //                       html.push('<td style="text-align:left;padding-left:10px;padding-right:10px;">'+details+'</td>');
    //                       html.push('<td style="text-align:left;color:cyan;padding-left:10px;padding-right:10px;">'+tcode+'</td>');
    //                       html.push('<td style="text-align:left;padding-left:10px;padding-right:10px;">'+tdate+'</td>');
    //                       html.push('<td style="text-align:left;padding-left:10px;padding-right:10px;border-right:1px solid white;">'+transinfo+'</td>');
    //                       html.push('<td style="text-align:right;color:lightgreen;padding-left:10px;padding-right:10px;"></td>');
    //                       html.push('<td style="text-align:right;color:lightgreen;padding-left:10px;padding-right:10px;"></td>');
    //                       html.push('<td style="text-align:right;color:lightgreen;padding-left:10px;padding-right:10px;border-right:1px solid white;"></td>');
    //                       html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;"></td>');
    //                       html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;"></td>');
    //                       html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;border-right:1px solid white;"></td>');
    //                       html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;">'+numberWithCommas(onhand)+'</td>');
    //                       html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;">'+numberWithCommas(ave_cost)+'</td>');
    //                       html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;">'+numberWithCommas(total_value)+'</td>');
    //                     html.push('</tr>');
    //                 } else if ((details == "Incoming")||(details == "Repack-In")||(details == "Adjust-Add")){
    //                     onhand = onhand + qty;  
    //                     ave_cost = ((ucost * qty) + prev_value)/onhand;
    //                     total_value = ave_cost * onhand;
    //                     html.push('<tr>');              
    //                       html.push('<td style="text-align:left;padding-left:10px;padding-right:10px;">'+details+'</td>');
    //                       html.push('<td style="text-align:left;color:cyan;padding-left:10px;padding-right:10px;">'+tcode+'</td>');
    //                       html.push('<td style="text-align:left;padding-left:10px;padding-right:10px;">'+tdate+'</td>');
    //                       html.push('<td style="text-align:left;padding-left:10px;padding-right:10px;border-right:1px solid white;">'+transinfo+'</td>');
    //                       html.push('<td style="text-align:right;color:lightgreen;padding-left:10px;padding-right:10px;">'+numberWithCommas(qty)+'</td>');
    //                       html.push('<td style="text-align:right;color:lightgreen;padding-left:10px;padding-right:10px;">'+numberWithCommas(ucost)+'</td>');
    //                       html.push('<td style="text-align:right;color:lightgreen;padding-left:10px;padding-right:10px;border-right:1px solid white;">'+numberWithCommas(qty * ucost)+'</td>');
    //                       html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;"></td>');
    //                       html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;"></td>');
    //                       html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;border-right:1px solid white;"></td>');
    //                       html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;">'+numberWithCommas(onhand)+'</td>');
    //                       html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;">'+numberWithCommas(ave_cost)+'</td>');
    //                       html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;">'+numberWithCommas(total_value)+'</td>');
    //                     html.push('</tr>');
    //                 } else if ((details == "Sale-Counter")||(details == "Sale-Credit")||(details == "Repack-Out")||(details == "Adjust-Minus")){
    //                     if (ucost > 0.00){
    //                         onhand = onhand - qty;
    //                         if (ave_cost == 0.00){
    //                             ave_cost = ucost;
    //                         }
    //                         total_value = ave_cost * onhand;
    //                         html.push('<tr>');             
    //                             html.push('<td style="text-align:left;padding-left:10px;padding-right:10px;">'+details+'</td>');
    //                             html.push('<td style="text-align:left;color:cyan;padding-left:10px;padding-right:10px;">'+tcode+'</td>');
    //                             html.push('<td style="text-align:left;padding-left:10px;padding-right:10px;">'+tdate+'</td>');
    //                             html.push('<td style="text-align:left;padding-left:10px;padding-right:10px;border-right:1px solid white;">'+transinfo+'</td>');
    //                             html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;"></td>');
    //                             html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;"></td>');
    //                             html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;border-right:1px solid white;"></td>');
    //                             html.push('<td style="text-align:right;color:lightsalmon;padding-left:10px;padding-right:10px;">'+numberWithCommas(qty)+'</td>');
    //                             html.push('<td style="text-align:right;color:lightsalmon;padding-left:10px;padding-right:10px;">'+numberWithCommas(ucost)+'</td>');
    //                             html.push('<td style="text-align:right;color:lightsalmon;padding-left:10px;padding-right:10px;border-right:1px solid white;">'+numberWithCommas(qty * ucost)+'</td>');
    //                             html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;">'+numberWithCommas(onhand)+'</td>');
    //                             html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;">'+numberWithCommas(ave_cost)+'</td>');
    //                             html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;">'+numberWithCommas(total_value)+'</td>');
    //                         html.push('</tr>');
    //                     }else{                          // Sales Return
    //                         qty = Math.abs(qty);        // Turn to positive for stock card display - in database return values are saved as negative numbers
    //                         ucost = Math.abs(ucost);
    //                         onhand = onhand + qty;
    //                         ave_cost = ((ucost * qty) + prev_value)/onhand;
    //                         // total_value = ave_cost * onhand;
    //                         details = 'Return';
    //                         html.push('<tr>');             
    //                             html.push('<td style="text-align:left;padding-left:10px;padding-right:10px;">'+details+'</td>');
    //                             html.push('<td style="text-align:left;color:cyan;padding-left:10px;padding-right:10px;">'+tcode+'</td>');
    //                             html.push('<td style="text-align:left;padding-left:10px;padding-right:10px;">'+tdate+'</td>');
    //                             html.push('<td style="text-align:left;padding-left:10px;padding-right:10px;border-right:1px solid white;">'+transinfo+'</td>');
    //                             html.push('<td style="text-align:right;color:lightgreen;padding-left:10px;padding-right:10px;">'+numberWithCommas(qty)+'</td>');
    //                             html.push('<td style="text-align:right;color:lightgreen;padding-left:10px;padding-right:10px;">'+numberWithCommas(ucost)+'</td>');
    //                             html.push('<td style="text-align:right;color:lightgreen;padding-left:10px;padding-right:10px;border-right:1px solid white;">'+numberWithCommas(qty * ucost)+'</td>');
    //                             html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;"></td>');
    //                             html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;"></td>');
    //                             html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;border-right:1px solid white;"></td>');
    //                             html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;">'+numberWithCommas(onhand)+'</td>');
    //                             html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;">'+numberWithCommas(ave_cost)+'</td>');
    //                             html.push('<td style="text-align:right;padding-left:10px;padding-right:10px;">'+numberWithCommas(total_value)+'</td>');
    //                         html.push('</tr>');
    //                     }
    //                 } 
    //                 prev_value = total_value;
    //             }

    //             $('.stockcard_content').html(html.join(''));
    //         }
    //     });
    // }); 
    
    $("#btn-print-report").click(function(){
        var branchcode = $('#lst-branchcode').val();
        let t_date = $("#date-tdate").val();
        let tdate = t_date.substring(6, 10) + '-' + t_date.substring(0, 2) + '-' + t_date.substring(3, 5);
        let reptype = $("#lst-reptype").val();

        let generatedby = $("#tns-generatedby").val();
  
        window.open("extensions/tcpdf/pdf/inventory_report.php?tdate="+tdate+"&branchcode="+branchcode+"&reptype="+reptype+"&generatedby="+generatedby, "_blank");
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
        document.getElementById("overall_assessment").innerHTML +
        '</body> ' +
        '</html>'
        window.location.href = location + window.btoa(excelTemplate);
    } 

    // function load_inventory_periods(){
    //     let branchcode = $('#lst-branchcode').val();
    //     var periods = new FormData();
    //     periods.append("branchcode", branchcode);
    //     $.ajax({
    //        url:"ajax/inventory_periods.ajax.php",
    //        method: "POST",
    //        data: periods,
    //        cache: false,
    //        contentType: false,
    //        processData: false,
    //        dataType:"json",
    //        success:function(answer){
    //             $(".inventoryPeriodsTable").DataTable().clear();
    //             var numRec = answer.length;
    //             for(var i = 0; i <= numRec; i++) {
    //                 let period = answer[i];

    //                 if (i != numRec){
    //                     // From
    //                     var inventoryfrom = period.inventoryfrom;
    //                     let inv_from= period.inventoryfrom;
    //                     let date_Obj = new Date(inv_from);
    //                     let option = { year: 'numeric', month: 'long', day: 'numeric' };
    //                     var inv_start = date_Obj.toLocaleDateString('en-US', option);

    //                     var _inv_from = inventoryfrom.split("-");
    //                     _inv_from = _inv_from[1] + "/" + _inv_from[2] + "/" + _inv_from[0];

    //                     let invnumber_from = period.invnumber_from;
    //                     var inventoryfromnextday = period.inventoryfromnextday;

    //                     // To
    //                     var inventoryto = period.inventoryto;
    //                     let inv_to = period.inventoryto;
    //                     let dateObj = new Date(inv_to);
    //                     let options = { year: 'numeric', month: 'long', day: 'numeric' };
    //                     var inv_end = dateObj.toLocaleDateString('en-US', options);

    //                     var _inv_to = inventoryto.split("-");
    //                     _inv_to = _inv_to[1] + "/" + _inv_to[2] + "/" + _inv_to[0];

    //                     let invnumber_to = period.invnumber_to;
    //                     var inventorytonextday = period.inventorytonextday;
                        
    //                     var button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnInvPeriod' inventoryfrom='"+inventoryfrom+"' inventoryfromnextday='"+inventoryfromnextday+"' _inv_from='"+_inv_from+"' inventoryto='"+inventoryto+"' inventorytonextday='"+inventorytonextday+"' _inv_to='"+_inv_to+"'><i class='icon-check'></i></button></td>";  
    //                     ip.row.add([inv_start, inv_end, button]); 
    //                 }else{
    //                     let l_date = $("#date-ldate").val();
    //                     if (l_date != ''){
    //                         let [m, d, y] = l_date.split("/");
    //                         let formattedDate = `${new Date(`${y}-${m}-${d}`).toLocaleString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}`;
    //                         ip.row.add([inv_end, formattedDate, button]); 
    //                     }
    //                 }
    //              }
    //              ip.draw();
    //        }
    //     })  	
    // }

    function load_inventory_periods() {
        let branchcode = $('#lst-branchcode').val();
        var periods = new FormData();
        periods.append("branchcode", branchcode);

        $.ajax({
            url: "ajax/inventory_periods.ajax.php",
            method: "POST",
            data: periods,
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

                    ip.row.add([inv_start, inv_end, button]);
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

    // Branch Selection
    $('#lst-branchcode').on('change', function() {
        $('#date-invfrom').val('');
        $('#date-invto').val('');
        $("#btn-export").prop('disabled', true);
        $(".overall_assessment").empty();
    });

    // Display Mode Selection
    $('#lst-displaymode').on('change', function () {
        if ($('#date-invfrom').val() !== '' && $('#date-invto').val() !== '') {
            $(".overall_assessment").empty();
        }
    });

    // Selected Inventory Period (From - To)
    $(".inventoryPeriodsTable tbody").on('click', '.btnInvPeriod', function () {
        $(".overall_assessment").empty();
        let branchcode = $('#lst-branchcode').val();
        $("#btn-export").prop('disabled', false);

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

        var inventory_period = new FormData();
        inventory_period.append("branchcode", branchcode);
        inventory_period.append("inventoryfrom", inventoryfrom);
        inventory_period.append("inventoryfromnextday", inventoryfromnextday);
        inventory_period.append("inventoryto", inventoryto);
        $.ajax({
            url:"ajax/inventory_matrix.ajax.php",
            method: "POST",
            data: inventory_period,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(answer){
                var html = [];
                let display_mode = $("#lst-displaymode").val();
                if (display_mode == '2'){  // -------------------------------------------------------------------------------------------------------------------
                    html.push('<div class="table-responsive" style="margin-top:-25px;margin-bottom:-28px;margin-left:18px;margin-right:18px;overflow-y: auto; overflow-x: auto; max-height: 500px;">');
                        html.push('<table class="table mx-auto w-auto productInventoryTable" style="border-collapse: separate; border-spacing: 0;">');
                            html.push('<thead>');
                                html.push('<tr>');
                                    html.push('<th class="table_head_left_fixed" style="position: sticky; left: 0; z-index: 10; background-color: #1f1f1f; padding-top:8px; padding-bottom:8px; min-width:400px;font-size:1.1em;">PRODUCT DESCRIPTION</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;color:#89fa91;">Beg Qty</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:110px;">Cost</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:85px;color:#89fa91;">Inc Qty</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:110px;">Cost</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:85px;color:#89fa91;">Rep In</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:110px;border-right:3px solid yellow;">Cost</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;color:#7FFF00;">INBOUND</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;border-right:3px solid yellow;color:aqua;">VALUE</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:85px;color:#ffd6ad;">Counter</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:110px;">Cost</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:85px;color:#ffd6ad;">Credit</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:110px;">Cost</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;color:#ffd6ad;">Rep Out</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:110px;border-right:3px solid yellow;">Cost</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;color:#fac189;">OUTBOUND</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;border-right:3px solid yellow;color:aqua;">VALUE</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;border-right:3px solid yellow;">STOCK</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;">ENDING</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:110px;">Cost</th>');
                                html.push('</tr>');
                            html.push('</thead>');
                            for(var i = 0; i < answer.length; i++) {
                                let matrix = answer[i];
                                let product = matrix.product_display_name;
                                let prodid = matrix.prodid;
                                let beginning_qty = parseInt(matrix.beginning_qty);
                                let beginning_tamount = Number(matrix.beginning_tamount);
                                let incoming_qty_total = parseInt(matrix.incoming_qty_total);
                                let incoming_tamount_total = Number(matrix.incoming_tamount_total);
                                let repin_qty = parseInt(matrix.repin_qty);
                                let repin_cost = Number(matrix.repin_cost);
                                let counter_qty = parseInt(matrix.counter_qty);
                                let counter_cost = Number(matrix.counter_cost);
                                let credit_qty = parseInt(matrix.credit_qty);
                                let credit_cost = Number(matrix.credit_cost);
                                let repout_qty = parseInt(matrix.repout_qty);
                                let repout_cost = Number(matrix.repout_cost);
                                let ending_qty = parseInt(matrix.ending_qty);
                                let ending_tamount = Number(matrix.ending_tamount);

                                let total_stockin = beginning_qty + incoming_qty_total + repin_qty;
                                let total_stockin_value = beginning_tamount + incoming_tamount_total + repin_cost;

                                let total_stockout = counter_qty + credit_qty + repout_qty;
                                let total_stockout_value = counter_cost + credit_cost + repout_cost;

                                let remaining_stock = total_stockin - total_stockout;

                                if ((beginning_qty != 0)||(incoming_qty_total !=0)||(counter_qty !=0)||(repin_qty !=0)||(repout_qty !=0)||(ending_qty != 0)){
                                    html.push('<tr>');         
                                        html.push('<td style="position: sticky; left: 0; z-index: 5; background-color: #1f1f1f; border-right: 1px solid white; font-size:1.1em; color:lightyellow;">' + product + '</td>');                              
                                        
                                        if (beginning_qty != 0){
                                            html.push('<td style="text-align:right;border-right:1px solid white;color:#89fa91;">'+beginning_qty+'</td>');
                                            html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(beginning_tamount)+'</td>');
                                        }else{
                                            html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                            html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                        }

                                        if (incoming_qty_total != 0){
                                            html.push('<td style="border-right:1px solid white;"><button style="z-index:2;width:80px;padding-top:1px;padding-bottom:1px;" type="button" class="btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon ml-2 btnIncoming" prodid="' + prodid + '" product="' + product + '" inventoryfromnextday="' + inventoryfromnextday + '" inventoryto="' + inventoryto + '">' +
                                                            numberWithCommasNoDecimal(incoming_qty_total) +
                                                        '</button></td>');         
                                            html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(incoming_tamount_total)+'</td>');
                                        }else{
                                            html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                            html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                        }

                                        if (repin_qty != 0){
                                            html.push('<td style="text-align:right;border-right:1px solid white;color:#89fa91;">'+numberWithCommasNoDecimal(repin_qty)+'</td>');
                                            html.push('<td style="text-align:right;border-right:4px solid yellow;">'+numberWithCommas(repin_cost)+'</td>');
                                        }else{
                                            html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                            html.push('<td style="text-align:right;border-right:4px solid yellow;"></td>');
                                        }                                    

                                        if (beginning_qty + incoming_qty_total + repin_qty != 0){
                                            html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.1em;font-weight:bold;color:#7FFF00;">'+numberWithCommasNoDecimal(total_stockin)+'</td>');   
                                            html.push('<td style="text-align:right;border-right:4px solid yellow;font-size:1.1em;font-weight:bold;color:aqua;">'+numberWithCommas(total_stockin_value)+'</td>');
                                        }else{
                                            html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.1em;font-weight:bold;color:#7FFF00;"></td>');   
                                            html.push('<td style="text-align:right;border-right:4px solid yellow;font-size:1.1em;font-weight:bold;color:aqua;"></td>');
                                        }

                                        if (counter_qty != 0){
                                            //html.push('<td style="text-align:right;border-right:1px solid white;color:#ffd6ad;">'+numberWithCommasNoDecimal(counter_qty)+'</td>');
                                            html.push('<td style="border-right:1px solid white;"><button style="z-index:2;width:80px;padding-top:1px;padding-bottom:1px;" type="button" class="btn btn-outline btn-sm bg-orange-400 border-orange-400 text-orange-400 btn-icon ml-2 btnCounter" prodid="' + prodid + '" product="' + product + '">' +
                                                            numberWithCommasNoDecimal(counter_qty) +
                                                        '</button></td>'); 
                                            html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(counter_cost)+'</td>');
                                        }else{
                                            html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                            html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                        }

                                        if (credit_qty != 0){
                                            //html.push('<td style="text-align:right;border-right:1px solid white;color:#ffd6ad;">'+numberWithCommasNoDecimal(credit_qty)+'</td>');
                                            html.push('<td style="border-right:1px solid white;"><button style="z-index:2;width:80px;padding-top:1px;padding-bottom:1px;" type="button" class="btn btn-outline btn-sm bg-orange-400 border-orange-400 text-orange-400 btn-icon ml-2 btnCredit" prodid="' + prodid + '" product="' + product + '" inventoryfromnextday="' + inventoryfromnextday + '" inventoryto="' + inventoryto + '">' +
                                                            numberWithCommasNoDecimal(credit_qty) +
                                                        '</button></td>'); 
                                            html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(credit_cost)+'</td>');
                                        }else{
                                            html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                            html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                        }

                                        if (repout_qty != 0){
                                            html.push('<td style="text-align:right;border-right:1px solid white;color:#ffd6ad;">'+numberWithCommasNoDecimal(repout_qty)+'</td>');
                                            html.push('<td style="text-align:right;border-right:4px solid yellow;">'+numberWithCommas(repout_cost)+'</td>');
                                        }else{
                                            html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                            html.push('<td style="text-align:right;border-right:4px solid yellow;"></td>');
                                        } 

                                        if (counter_qty + credit_qty + repout_qty != 0){
                                            html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.1em;font-weight:bold;color:#fac189;">'+numberWithCommasNoDecimal(total_stockout)+'</td>');   
                                            html.push('<td style="text-align:right;border-right:4px solid yellow;font-size:1.1em;font-weight:bold;color:aqua;">'+numberWithCommas(total_stockout_value)+'</td>');
                                        }else{
                                            html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.1em;font-weight:bold;color:#7FFF00;"></td>');   
                                            html.push('<td style="text-align:right;border-right:4px solid yellow;font-size:1.1em;font-weight:bold;color:aqua;"></td>');
                                        }

                                        html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.1em;font-weight:bold;">'+numberWithCommasNoDecimal(remaining_stock)+'</td>');

                                        if (ending_qty != 0){
                                            html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommasNoDecimal(ending_qty)+'</td>');
                                            html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(ending_tamount)+'</td>');
                                        }else{
                                            html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                            html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                        }                                    
                                    html.push('</tr>');
                                }
                            }
                        html.push('</table>');
                    html.push('</div>');
                }else{  // --------------------------------------------------------------------------------------------------------------------------------------
                    html.push('<div class="table-responsive" style="margin-top:-25px;margin-bottom:-28px;margin-left:18px;margin-right:18px;overflow-y: auto; overflow-x: auto; max-height: 500px;">');
                        html.push('<table class="table mx-auto w-auto productInventoryTable" style="border-collapse: separate; border-spacing: 0;">');
                            html.push('<thead>');
                                html.push('<tr>');
                                    html.push('<th class="table_head_left_fixed" style="position: sticky; left: 0; z-index: 10; background-color: #1f1f1f; padding-top:8px; padding-bottom:8px; min-width:400px;font-size:1.1em;">PRODUCT DESCRIPTION</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;color:#89fa91;">Beg Qty</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:85px;color:#89fa91;">Inc Qty</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:85px;color:#89fa91;">Rep In</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:85px;color:#89fa91;">Adj +</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;color:#7FFF00;border-right:3px solid yellow;border-left:3px solid yellow;">INBOUND</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:85px;color:#ffd6ad;">Counter</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:85px;color:#ffd6ad;">Credit</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;color:#ffd6ad;">Rep Out</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;color:#ffd6ad;">Adj -</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;color:#fac189;border-right:3px solid yellow;border-left:3px solid yellow;">OUTBOUND</th>');

                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:110px;border-right:3px solid yellow;color:#fb88fc;" title="Average Product Cost (Beginning - Ending)">AVE UNIT COST');
                                        html.push('<span style="visibility: hidden; background-color: rgba(0, 0, 0, 0.75); color: #fff; padding: 5px 10px; border-radius: 5px; position: absolute; top: 0; left: 100%; z-index: 1000; white-space: nowrap; opacity: 0;">Average Product Cost (Beginning - Ending)</span>');
                                    html.push('</th>');

                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:110px;border-right:3px solid yellow;color:cyan;" title="Inbound Qty - Outbound Qty\n\nNegative [Inbound < Outbound]\nPositive [Inbound > Outbound]">STOCK QTY');
                                        html.push('<span style="visibility: hidden; background-color: rgba(0, 0, 0, 0.75); color: #fff; padding: 5px 10px; border-radius: 5px; position: absolute; top: 0; left: 100%; z-index: 1000; white-space: nowrap; opacity: 0;">Inbound Qty - Outbound Qty\n\nNegative [Inbound < Outbound]\nPositive [Inbound > Outbound]</span>');
                                    html.push('</th>');
                                    //html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;border-right:1px solid white;color:cyan">STOCK QTY</th>');

                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:130px;border-right:3px solid yellow;color:cyan;" title="Stock Qty x Average Unit Cost\n\nNote: If the Stock Average Cost\nis negative, it means that the\nnumber of quantity Sold is greater than\nthe number of Inbound quantity\n\nThus, it is interpreted as loss in inventory,\nbut positive revenue or gain in sales.\n\nStatus = OVERSOLD">STOCK AVE COST');
                                        html.push('<span style="visibility: hidden; background-color: rgba(0, 0, 0, 0.75); color: #fff; padding: 5px 10px; border-radius: 5px; position: absolute; top: 0; left: 100%; z-index: 1000; white-space: nowrap; opacity: 0;">Stock Qty x Average Unit Cost\n\nNote: If the Stock Average Cost\nis negative, it means that the\nnumber of qty Sold is greater than\nthe number of Inbound quantity\n\nThus, it is interpreted as loss in inventory,\nbut positive revenue or gain in sales.\n\nStatus = OVERSOLD</span>');
                                    html.push('</th>');
                                    //html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:130px;border-right:3px solid yellow;color:cyan">STOCK AVE COST</th>');
                                    
                                    // Do not display Ending Header if Last option in Period Datatable is selected
                                    if ($("#date-invto").val() != $("#date-ldate").val()){
                                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;" title="Ending Inventory Count">END QTY');
                                            html.push('<span style="visibility: hidden; background-color: rgba(0, 0, 0, 0.75); color: #fff; padding: 5px 10px; border-radius: 5px; position: absolute; top: 0; left: 100%; z-index: 1000; white-space: nowrap; opacity: 0;">Ending Inventory Count</span>');
                                        html.push('</th>');
                                        //html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;">END QTY</th>');

                                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:130px;" title="End Qty x Average Unit Cost">END AVE COST');
                                            html.push('<span style="visibility: hidden; background-color: rgba(0, 0, 0, 0.75); color: #fff; padding: 5px 10px; border-radius: 5px; position: absolute; top: 0; left: 100%; z-index: 1000; white-space: nowrap; opacity: 0;">End Qty x Average Unit Cost</span>');
                                        html.push('</th>');

                                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;border-left:3px solid yellow;color:#57fa83;" title="Ending Inventory Actual Unit Cost">END ACTUAL COST');
                                            html.push('<span style="visibility: hidden; background-color: rgba(0, 0, 0, 0.75); color: #fff; padding: 5px 10px; border-radius: 5px; position: absolute; top: 0; left: 100%; z-index: 1000; white-space: nowrap; opacity: 0;">Ending Inventory Actual Unit Cost</span>');
                                        html.push('</th>');

                                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;border-left:3px solid yellow;" title="End Qty - Stock Qty">VARIATION QTY');
                                            html.push('<span style="visibility: hidden; background-color: rgba(0, 0, 0, 0.75); color: #fff; padding: 5px 10px; border-radius: 5px; position: absolute; top: 0; left: 100%; z-index: 1000; white-space: nowrap; opacity: 0;">End Qty - Stock Qty</span>');
                                        html.push('</th>');

                                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;border-left:3px solid yellow;" title="Variation Qty x Average Unit Cost\n\nNegative -> Shortage\nPositive -> Overage\nNo Value -> Perfect">VARIATION AVE COST');
                                            html.push('<span style="visibility: hidden; background-color: rgba(0, 0, 0, 0.75); color: #fff; padding: 5px 10px; border-radius: 5px; position: absolute; top: 0; left: 100%; z-index: 1000; white-space: nowrap; opacity: 0;">Variation Qty x Average Unit Cost\n\nNegative -> Shortage\nPositive -> </span>');
                                        html.push('</th>');
                                    }
                                html.push('</tr>');
                            html.push('</thead>');
                            var total_ending_cost = 0.00;
                            var total_ending_average_cost = 0.00;
                            var total_stock_cost = 0.00;
                            var total_variation_average_cost = 0.00;
                            for(var i = 0; i < answer.length; i++) {
                                let matrix = answer[i];
                                let product = matrix.product_display_name;
                                let prodid = matrix.prodid;

                                let beginning_qty = parseInt(matrix.beginning_qty);
                                let beginning_ucost = Number(matrix.beginning_ucost);
                                let beginning_tamount = Number(matrix.beginning_tamount);

                                let incoming_qty_total = parseInt(matrix.incoming_qty_total);
                                let incoming_average_ucost = Number(matrix.incoming_average_ucost);
                                let incoming_tamount_total = Number(matrix.incoming_tamount_total);

                                let repin_qty = parseInt(matrix.repin_qty);
                                let repin_average_ucost = Number(matrix.repin_average_ucost);
                                let repin_cost = Number(matrix.repin_cost);

                                let adjin_qty = parseInt(matrix.adjin_qty);
                                let adjin_average_ucost = Number(matrix.adjin_average_ucost);
                                let adjin_cost = Number(matrix.adjin_cost);

                                let counter_qty = parseInt(matrix.counter_qty);
                                let counter_average_ucost = Number(matrix.counter_average_ucost);
                                let counter_cost = Number(matrix.counter_cost);

                                let credit_qty = parseInt(matrix.credit_qty);
                                let credit_average_ucost = Number(matrix.credit_average_ucost);
                                let credit_cost = Number(matrix.credit_cost);

                                let repout_qty = parseInt(matrix.repout_qty);
                                let repout_cost = Number(matrix.repout_cost);

                                let adjout_qty = parseInt(matrix.adjout_qty);
                                let adjout_average_ucost = Number(matrix.adjout_average_ucost);
                                let adjout_cost = Number(matrix.adjout_cost);                                

                                let ending_qty = parseInt(matrix.ending_qty);
                                let ending_ucost = Number(matrix.ending_ucost);
                                let ending_tamount = Number(matrix.ending_tamount);

                                let average_ucost = (beginning_ucost + incoming_average_ucost + counter_average_ucost + credit_average_ucost + ending_ucost) / 5;
                                let average_ending_cost = ending_qty * average_ucost;

                                let total_stockin = beginning_qty + incoming_qty_total + repin_qty + adjin_qty;
                                let total_stockin_value = beginning_tamount + incoming_tamount_total + repin_cost + adjin_cost;

                                let total_stockout = counter_qty + credit_qty + repout_qty + adjout_qty;
                                let total_stockout_value = counter_cost + credit_cost + repout_cost + adjout_cost;

                                let remaining_stock = total_stockin - total_stockout;
                                let remaining_stock_cost = average_ucost * remaining_stock;

                                total_stock_cost = total_stock_cost + remaining_stock_cost;
                                total_ending_average_cost = total_ending_average_cost + average_ending_cost; 
                                total_ending_cost = total_ending_cost + ending_tamount;

                                let variation_qty = ending_qty - remaining_stock;
                                let variation_average_cost = variation_qty * average_ucost;

                                total_variation_average_cost = total_variation_average_cost + variation_average_cost;

                                if ((beginning_qty != 0)||(incoming_qty_total !=0)||(counter_qty !=0)||(repin_qty !=0)||(repout_qty !=0)||(ending_qty != 0)){
                                    html.push('<tr>');         
                                        html.push('<td style="position: sticky; left: 0; z-index: 5; background-color: #1f1f1f; border-right: 1px solid white; font-size:1.1em; color:lightyellow;">' + product + '</td>');                              
                                        
                                        if (beginning_qty != 0){
                                            html.push('<td style="text-align:right;border-right:1px solid white;color:#89fa91;">'+beginning_qty+'</td>');
                                        }else{
                                            html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                        }

                                        if (incoming_qty_total != 0){
                                            html.push('<td style="border-right:1px solid white;"><button style="z-index:2;width:80px;padding-top:1px;padding-bottom:1px;" type="button" class="btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon ml-2 btnIncoming" prodid="' + prodid + '" product="' + product + '" inventoryfromnextday="' + inventoryfromnextday + '" inventoryto="' + inventoryto + '">' +
                                                            numberWithCommasNoDecimal(incoming_qty_total) +
                                                        '</button></td>');         
                                        }else{
                                            html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                        }

                                        if (repin_qty != 0){
                                            html.push('<td style="text-align:right;border-right:1px solid white;color:#89fa91;">'+numberWithCommasNoDecimal(repin_qty)+'</td>');
                                        }else{
                                            html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                        }       
                                        
                                        if (adjin_qty != 0){
                                            html.push('<td style="text-align:right;border-right:1px solid white;color:#89fa91;">'+numberWithCommasNoDecimal(adjin_qty)+'</td>');
                                        }else{
                                            html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                        }                                         

                                        // Inbound
                                        if (beginning_qty + incoming_qty_total + repin_qty + adjin_qty != 0){
                                            html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.3em;font-weight:bold;color:#7FFF00;border-left:3px solid yellow;">'+numberWithCommasNoDecimal(total_stockin)+'</td>');   
                                        }else{
                                            html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.1em;font-weight:bold;color:#7FFF00;border-left:3px solid yellow;"></td>');   
                                        }

                                        if (counter_qty != 0){
                                            html.push('<td style="border-right:1px solid white;border-left:3px solid yellow;"><button style="z-index:2;width:80px;padding-top:1px;padding-bottom:1px;" type="button" class="btn btn-outline btn-sm bg-orange-400 border-orange-400 text-orange-400 btn-icon ml-2 btnCounter" prodid="' + prodid + '" product="' + product + '" inventoryfromnextday="' + inventoryfromnextday + '" inventoryto="' + inventoryto + '">' +
                                                            numberWithCommasNoDecimal(counter_qty) +
                                                        '</button></td>'); 
                                        }else{
                                            html.push('<td style="text-align:right;border-right:1px solid white;border-left:3px solid yellow;"></td>');
                                        }

                                        if (credit_qty != 0){
                                            html.push('<td style="border-right:1px solid white;"><button style="z-index:2;width:80px;padding-top:1px;padding-bottom:1px;" type="button" class="btn btn-outline btn-sm bg-orange-400 border-orange-400 text-orange-400 btn-icon ml-2 btnCredit" prodid="' + prodid + '" product="' + product + '" inventoryfromnextday="' + inventoryfromnextday + '" inventoryto="' + inventoryto + '">' +
                                                            numberWithCommasNoDecimal(credit_qty) +
                                                        '</button></td>');  
                                        }else{
                                            html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                        }

                                        if (repout_qty != 0){
                                            html.push('<td style="text-align:right;border-right:1px solid white;color:#ffd6ad;">'+numberWithCommasNoDecimal(repout_qty)+'</td>');         
                                        }else{
                                            html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                        } 

                                        if (adjout_qty != 0){
                                            html.push('<td style="text-align:right;border-right:1px solid white;color:#ffd6ad;">'+numberWithCommasNoDecimal(adjout_qty)+'</td>');         
                                        }else{
                                            html.push('<td style="text-align:right;border-right:1px solid white;"></td>');
                                        } 
                                        
                                        // Outbound
                                        if (counter_qty + credit_qty + repout_qty + adjout_qty != 0){
                                            html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.3em;font-weight:bold;color:#fac189;border-left:3px solid yellow;">'+numberWithCommasNoDecimal(total_stockout)+'</td>');   
                                        }else{
                                            html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.1em;font-weight:bold;color:#7FFF00;border-left:3px solid yellow;"></td>');   
                                        }

                                        // Average Unit Cost (Beginning - Ending)
                                        if ((remaining_stock != 0)||(ending_qty != 0)){
                                            html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.3em;font-weight:bold;color:#fb88fc;border-left:3px solid yellow;">'+numberWithCommas(average_ucost)+'</td>');
                                        }else{
                                            html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.3em;font-weight:bold;color:#fb88fc;border-left:3px solid yellow;"></td>');
                                        }
                                        // Stock (Inbound - Outbound)
                                        if (remaining_stock != 0){
                                            if (remaining_stock < 0){
                                                html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.3em;font-weight:bold;color:#ff7161;border-left:3px solid yellow;">'+numberWithCommasNoDecimal(remaining_stock)+'</td>');
                                                html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.3em;font-weight:bold;color:#ff7161;border-left:1px solid white;">'+numberWithCommas(remaining_stock_cost)+'</td>');
                                            }else{
                                                html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.3em;font-weight:bold;color:cyan;border-left:3px solid yellow;">'+numberWithCommasNoDecimal(remaining_stock)+'</td>');
                                                html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.3em;font-weight:bold;color:cyan;border-left:1px solid white;">'+numberWithCommas(remaining_stock_cost)+'</td>');
                                            }
                                        }else{
                                            html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.1em;font-weight:bold;border-left:3px solid yellow;"></td>');
                                            html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.1em;font-weight:bold;border-left:1px solid white;"></td>');
                                        }

                                        // Do not display Ending Header if Last option in Period Datatable is selected
                                        // Latest Inventory Date - Current or Selected Date
                                        if ($("#date-invto").val() != $("#date-ldate").val()){
                                            if (ending_qty != 0){
                                                html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.3em;border-left:3px solid yellow;font-weight:bold;">'+numberWithCommasNoDecimal(ending_qty)+'</td>');
                                                html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.3em;border-left:1px solid white;font-weight:bold;">'+numberWithCommas(average_ending_cost)+'</td>');
                                                html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.3em;border-left:3px solid yellow;font-weight:bold;color:#57fa83;">'+numberWithCommas(ending_tamount)+'</td>');
                                            }else{
                                                html.push('<td style="text-align:right;border-right:1px solid white;border-left:3px solid yellow;"></td>');
                                                html.push('<td style="text-align:right;border-right:1px solid white;border-left:1px solid white;"></td>');
                                                html.push('<td style="text-align:right;border-right:1px solid white;border-left:3px solid yellow;"></td>');
                                            } 

                                            if (variation_qty != 0){
                                                if (variation_qty < 0){
                                                    html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.3em;border-left:3px solid yellow;font-weight:bold;color:#ff7161;">'+numberWithCommasNoDecimal(variation_qty)+'</td>');
                                                    html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.3em;border-left:3px solid yellow;font-weight:bold;color:#ff7161;">'+numberWithCommas(variation_average_cost)+'</td>');
                                                }else{
                                                    html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.3em;border-left:3px solid yellow;font-weight:bold;color:#57fa83;">'+numberWithCommasNoDecimal(variation_qty)+'</td>');
                                                    html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.3em;border-left:3px solid yellow;font-weight:bold;color:#57fa83;">'+numberWithCommas(variation_average_cost)+'</td>');
                                                }
                                            }else{
                                                html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.3em;border-left:3px solid yellow;font-weight:bold;"></td>');
                                                html.push('<td style="text-align:right;border-right:1px solid white;font-size:1.3em;border-left:3px solid yellow;font-weight:bold;"></td>');
                                            }
                                        }                                   
                                    html.push('</tr>');
                                }
                            }


                            html.push('<tr>');
                                html.push('<td colspan="12" style="text-align:right;border:1px solid white;font-size:1.3em;font-weight:bold;color:yellow;">TOTAL INVENTORY COST</td>');
                                html.push('<td colspan="2" style="text-align:right;border:1px solid white;font-size:1.3em;font-weight:bold;color:cyan;border-left:3px solid yellow;">'+numberWithCommas(total_stock_cost)+'</td>');

                                if ($("#date-invto").val() != $("#date-ldate").val()){
                                    html.push('<td colspan="2" style="text-align:right;border:1px solid white;font-size:1.3em;font-weight:bold;color:white;border-left:3px solid yellow;">'+numberWithCommas(total_ending_average_cost)+'</td>');
                                    html.push('<td style="text-align:right;border:1px solid white;font-size:1.3em;font-weight:bold;color:#57fa83;border-left:3px solid yellow;">'+numberWithCommas(total_ending_cost)+'</td>');
                                    if (total_variation_average_cost < 0.00){
                                        html.push('<td colspan="2" style="text-align:right;border:1px solid white;font-size:1.3em;font-weight:bold;color:#ff7161;;border-left:1px solid white;">'+numberWithCommas(total_variation_average_cost)+'</td>');
                                    }else{
                                        html.push('<td colspan="2" style="text-align:right;border:1px solid white;font-size:1.3em;font-weight:bold;color:#57fa83;border-left:1px solid white;">'+numberWithCommas(total_variation_average_cost)+'</td>');
                                    }
                                }
                            html.push('</tr>');
                        html.push('</table>');
                    html.push('</div>');                    
                } // --------------------------------------------------------------------------------------------------------------------------------------------   
                $('.overall_assessment').html(html.join(''));
                $('#tns-search').prop('disabled', false);
                $('#tns-search').focus();
            }
        });
    }); 

    function filterProductInventoryTable() {
        let keyword = $('#tns-search').val().toLowerCase().trim();

        $('.productInventoryTable tbody tr').each(function() {
            let productDesc = $(this).find('td:first').text().toLowerCase();

            if (productDesc.indexOf(keyword) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    // Bind the function to keyup event
    $('#tns-search').on('keyup', filterProductInventoryTable);    

    $('#lbl-tns-search').on('click', function () {
        $('#tns-search').val('');
        filterProductInventoryTable();
    });   

    // INCOMING DETAILS
    // button inside <table>
    $(".overall-assessment-form").on("click", "button.btnIncoming", function(){
        $('#modal-incoming-product-list').modal('show');

        let branchcode = $('#lst-branchcode').val(); 
        let prodid = $(this).attr('prodid');
        let product = $(this).attr('product');
        let inventoryfromnextday = $(this).attr('inventoryfromnextday');
        let inventoryto = $(this).attr('inventoryto');

        $('#product_name').text(product);

        var incoming_period = new FormData();
        incoming_period.append("branchcode", branchcode);
        incoming_period.append("prodid", prodid);
        incoming_period.append("inventoryfromnextday", inventoryfromnextday);
        incoming_period.append("inventoryto", inventoryto);
        $.ajax({
            url:"ajax/incoming_period.ajax.php",
            method: "POST",
            data: incoming_period,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(answer){
                $(".incomingTable").DataTable().clear();
                for(var i = 0; i < answer.length; i++) {
                    let incoming = answer[i];

                    let del_date = incoming.deldate;
                    let deldate = del_date.split("-");
                    deldate = deldate[1] + "/" + deldate[2] + "/" + deldate[0];

                    let name = incoming.name;
                    let po_number = incoming.ponumber;
                    let iscode = incoming.iscode;
                    let del_number = incoming.delnumber;
                    let qty = numberWithCommas(incoming.qty);
                    let tamount = numberWithCommas(incoming.tamount);

                    let ponumber = "<td><button style='font-size:1em;' type='button' class='btn btn-outline btn-sm bg-orange-400 border-orange-400 text-orange-400 btn-icon border-2 ml-2 btnPurchaseForm' po_number='"+po_number+"'>" + po_number + "</button></td>";  
                    let delnumber = "<td><button style='font-size:1em;' type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon border-2 ml-2 btnIncomingForm' del_number='"+del_number+"'>" + del_number + "</button></td>";  

                    ipl.row.add([deldate, name, ponumber, iscode, delnumber, qty, tamount]);
                }
                ipl.draw();
            }
        });
    });

    // button inside Data Table
    $(".incomingTable tbody").on('click', '.btnIncomingForm', function () {
        let del_number = $(this).attr('del_number');
        window.open("extensions/tcpdf/pdf/incoming_view.php?delnumber="+del_number, "_blank");
    });

    $(".incomingTable tbody").on('click', '.btnPurchaseForm', function () {
        let po_number = $(this).attr('po_number');
        window.open("extensions/tcpdf/pdf/purchaseorder_view.php?ponumber="+po_number, "_blank");
    });

    // CREDIT DETAILS
    // button inside <table>
    $(".overall-assessment-form").on("click", "button.btnCredit", function(){
        $('#modal-credit-product-list').modal('show');

        let branchcode = $('#lst-branchcode').val(); 
        let prodid = $(this).attr('prodid');
        let product = $(this).attr('product');
        let inventoryfromnextday = $(this).attr('inventoryfromnextday');
        let inventoryto = $(this).attr('inventoryto');

        $('#prod_name').text(product);

        var credit_period = new FormData();
        credit_period.append("branchcode", branchcode);
        credit_period.append("prodid", prodid);
        credit_period.append("inventoryfromnextday", inventoryfromnextday);
        credit_period.append("inventoryto", inventoryto);
        $.ajax({
            url:"ajax/credit_period.ajax.php",
            method: "POST",
            data: credit_period,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(answer){
                $(".creditTable").DataTable().clear();
                for(var i = 0; i < answer.length; i++) {
                    let credit = answer[i];

                    let sale_date = credit.sdate;
                    let sdate = sale_date.split("-");
                    sdate = sdate[1] + "/" + sdate[2] + "/" + sdate[0];

                    let name = credit.name;
                    let inv_no = credit.invno;
                    let qty = numberWithCommas(credit.qty);
                    let tamount = numberWithCommas(credit.tamount);

                    let invno = "<td><button style='font-size:1em;' type='button' class='btn btn-outline btn-sm bg-orange-400 border-orange-400 text-orange-400 btn-icon border-2 ml-2 btnCreditForm' inv_no='"+inv_no+"'>" + inv_no + "</button></td>";

                    cred_table.row.add([sdate, name, invno, qty, tamount]);
                }
                cred_table.draw();
            }
        });
    });

    // COUNTER DETAILS
    // button inside <table>
    $(".overall-assessment-form").on("click", "button.btnCounter", function(){
        $('#modal-counter-product-list').modal('show');

        let branchcode = $('#lst-branchcode').val(); 
        let prodid = $(this).attr('prodid');
        let product = $(this).attr('product');
        let inventoryfromnextday = $(this).attr('inventoryfromnextday');
        let inventoryto = $(this).attr('inventoryto');

        $('#product_desc').text(product);

        var counter_period = new FormData();
        counter_period.append("branchcode", branchcode);
        counter_period.append("prodid", prodid);
        counter_period.append("inventoryfromnextday", inventoryfromnextday);
        counter_period.append("inventoryto", inventoryto);
        $.ajax({
            url:"ajax/counter_period.ajax.php",
            method: "POST",
            data: counter_period,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(answer){
                $(".counterTable").DataTable().clear();
                for(var i = 0; i < answer.length; i++) {
                    let counter = answer[i];

                    let sale_date = counter.sdate;
                    let sdate = sale_date.split("-");
                    sdate = sdate[1] + "/" + sdate[2] + "/" + sdate[0];

                    let inv_no = counter.invno;
                    let qty = numberWithCommas(counter.qty);
                    let tamount = numberWithCommas(counter.tamount);

                    let invno = "<td><button style='font-size:1em;' type='button' class='btn btn-outline btn-sm bg-orange-400 border-orange-400 text-orange-400 btn-icon border-2 ml-2 btnCounterForm' inv_no='"+inv_no+"'>" + inv_no + "</button></td>";

                    counter_table.row.add([sdate, invno, qty, tamount]);
                }
                counter_table.draw();
            }
        });
    });    

    // button inside Data Table
    $(".creditTable tbody").on('click', '.btnCreditForm', function () {
        let inv_no = $(this).attr('inv_no');
        let reprinted = 'VIEWING ONLY';
        window.open("extensions/tcpdf/pdf/deliverysale.php?invno="+inv_no+"&reprinted="+reprinted, "_blank");
    });

    function numberWithCommasNoDecimal(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function showTooltip(element) {
        var tooltip = element.querySelector('span');
        if (tooltip) {
            tooltip.style.visibility = 'visible';
            tooltip.style.opacity = '1';
        }
    }

    // Function to hide the tooltip when mouse leaves the element
    function hideTooltip(element) {
        var tooltip = element.querySelector('span');
        if (tooltip) {
            tooltip.style.visibility = 'hidden';
            tooltip.style.opacity = '0';
        }
    }
 });