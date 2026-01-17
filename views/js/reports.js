var transaction = "Stock Replenishment";

$('#transaction').on("change", function(){
  transaction = $("#transaction").val();
}); 

// $("#btnCategoryDescription").click(function(){
//   var date_range = $("#date_range").val();
//   var start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
//   var end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
//   var category = $("#category").val();
//   var trans_type = transaction;
// });


$("#btnCategoryDescription").click(function(){
  var date_range = $("#date_range").val();
  var start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
  var end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
  var category = $("#category").val();
  var trans_type = transaction;
  window.open("extensions/tcpdf/pdf/catdescreport.php?start_date="+start_date+"&end_date="+end_date+"&category="+category+"&trans_type="+trans_type, "_blank");
});

$("#btnDetailedSequence").click(function(){
  var date_range = $("#date_range").val();
  var start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
  var end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
  var category = $("#category").val();
  var trans_type = transaction;

  if ($("#transaction").val() == "Stock Replenishment"){
    window.open("extensions/tcpdf/pdf/sequencereport.php?start_date="+start_date+"&end_date="+end_date+"&category="+category+"&trans_type="+trans_type, "_blank");
  }else{
    window.open("extensions/tcpdf/pdf/sequencereportout.php?start_date="+start_date+"&end_date="+end_date+"&category="+category+"&trans_type="+trans_type, "_blank");
  }
});