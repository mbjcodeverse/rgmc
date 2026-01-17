// $(".productInventoryTable").DataTable({
// 	autoWidth: true,
//     scrollY: 409,
//     pagelength: 15,
//     lengthMenu: [[15, 50, -1], [15, 50, "All"]],
//     dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
//             language: {
//                 search: '<span>Filter:</span> _INPUT_',
//                 searchPlaceholder: 'Type to filter...',
//                 lengthMenu: '<span>Show:</span> _MENU_',
//                 paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
//             }
// });

$("div.dataTables_filter input").focus();

pl.column(2).visible(false);	
$('.transactionProductsTable tbody').on('dblclick', 'tr', function () {
  let idx = pl.row(this).index();  
  let itemid = pl.cell(idx, 2).data();			// get Item ID - hidden column  
  $('#stockcard').modal('show');  
  $("#product_name").html('');              // clear Product Name first

// $(".productInventoryTable").on("click", "tbody .btnStockcard", function(){
//   var itemid = $(this).attr("itemid");  
  var data = new FormData();                     
  data.append("itemid", itemid);
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

                    if (details == "Incoming"){
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
  })
}); 