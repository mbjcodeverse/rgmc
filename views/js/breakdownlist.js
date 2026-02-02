if (!$.fn.DataTable.isDataTable('.machinebreakdownListTable')) {
    var slst = $('.machinebreakdownListTable').DataTable({
        deferRender: true,
        processing: true,
        autoWidth: true,
        scrollY: 360,
        pageLength: 25,
        lengthMenu: [[25, 50], [25, 50]],
        dom: '<"datatable-header"><"extra-row"> <"datatable-scroll"t><"datatable-footer"fp>',
        language: {
            loadingRecords: 'Please wait - loading...',
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
            search: '<span>Filter:</span> _INPUT_',
            searchPlaceholder: 'Type to filter...',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 
                'first': 'First', 
                'last': 'Last', 
                'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 
                'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' 
            }
        }
    });
}

$(function() {
    $("#btn-new").click(function(){
        clearform();
    }); 

    function clearform(){
        $("#sel-classcode").val('').trigger('change');
        $("#sel-failuretype").val('').trigger('change');
        $("#tns-details").val("");
        $("#tns-breakid").val("");
    }

    $("#lbl-lst-classcode").click(function(){
        $("#lst-classcode").val('').trigger('change');
    });

    $("#lbl-lst-failuretype").click(function(){
        $("#lst-failuretype").val('').trigger('change');
    });

    $(".form-machine-breakdown").submit(function (e) {
        e.preventDefault();
        swal.fire({
            title: 'Do you want to save new machine breakdown details?',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Save it!',
            cancelButtonText: 'Cancel!',
            confirmButtonClass: 'btn btn-outline-success',
            cancelButtonClass: 'btn btn-outline-danger',
            allowOutsideClick: false,
            buttonsStyling: false
        }).then(function(result) {
            if(result.value) {
                let trans_type = $("#trans_type").val();
                let classcode = $("#sel-classcode").val();
                let failuretype = $("#sel-failuretype").val();
                let details = $("#tns-details").val();
                let breakid = $("#tns-breakid").val();

                // alert(classcode + ' ' + failuretype + ' ' + details + ' ' + breakid);

                var breakdown = new FormData();
                breakdown.append("trans_type", trans_type);
                breakdown.append("classcode", classcode);
                breakdown.append("failuretype", failuretype);
                breakdown.append("details", details);
                breakdown.append("breakid", breakid);

                $.ajax({
                    url:"ajax/machine_save_breakdown.ajax.php",
                    method: "POST",
                    data: breakdown,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType:"text",
                    success:function(answer){
                    },
                    error: function () {
                        alert("Oops. Something went wrong!");
                    },
                    complete: function () {
                        swal.fire({
                            title: 'Machine breakdown details successfully saved!',
                            type: 'success',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        clearForm();
                    }
                });
            }
        });        
    });

    $('#lst-classcode, #lst-failuretype').on("change", function() {
        let classcode = $("#lst-classcode").val();
        let failuretype = $("#lst-failuretype").val();
        
        var data = new FormData();
        data.append("classcode", classcode);
        data.append("failuretype", failuretype);
        
        $.ajax({
            url: "ajax/machine_breakdown_list.ajax.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(answer) {
                $(".machinebreakdownListTable").DataTable().clear();   
                for (var i = 0; i < answer.length; i++) {
                    var mt = answer[i];
    
                    var breakid = mt.breakid;
                    var classname = mt.classname;
                    var failuretype = mt.failuretype;
                    var details = mt.details;
    
                    var button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnBreakdown' breakid='" + breakid + "'><i class='icon-pencil3'></i></button></td>";
                    slst.row.add([classname, failuretype, details, button])
                }
                slst.draw();
            },
            beforeSend: function() {},
            complete: function() {
                $(".machinetrackingListTable td").css({
                    "padding-top": "5px",
                    "padding-bottom": "5px"
                });
            }
        });
    });
    
    $("#lst-classcode").val('').trigger('change');

    $(".machinebreakdownListTable tbody").on("click", "button.btnBreakdown", function(){
        $("#modal-search-breakdown").modal("hide");
        $("#trans_type").val("Update");
        var breakid = $(this).attr("breakid");
        var data = new FormData();
        data.append("breakid", breakid);
        $.ajax({
            url:"ajax/machine_breakdown_get_record.ajax.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(answer){
                $('#trans_type').val('Update');
                $("#sel-classcode").val(answer["classcode"]).trigger('change');
                $("#sel-failuretype").val(answer["failuretype"]).trigger('change');
                $("#tns-details").val(answer["details"]);
                $("#tns-breakid").val(answer["breakid"]);
            }
        })
    });    
});