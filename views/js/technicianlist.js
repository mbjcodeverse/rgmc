if (!$.fn.DataTable.isDataTable('.technicianListTable')) {
    var tlst = $('.technicianListTable').DataTable({
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
    $('#modal-search-technicianlist').on('shown.bs.modal', function () {
        tlst.search('').draw();
        tlst.table().container().querySelector('.dataTables_filter input').focus(); 
        $("#lst-machineid").val('').trigger('change');
        $("#lst-datemode").val('Reported').trigger('change');
        $("#lst-curstatus").val('').trigger('change');

        $('#lst_date_range').data('daterangepicker').setStartDate(moment('2025-12-01'));
        $('#lst_date_range').data('daterangepicker').setEndDate(moment());
    });

    $("#lbl-lst-date-range").click(function(){
        $('#lst_date_range').data('daterangepicker').setStartDate(moment('2025-12-01'));
        $('#lst_date_range').data('daterangepicker').setEndDate(moment());
        
        tlst.search('').draw();
        tlst.table().container().querySelector('.dataTables_filter input').focus(); 
    });

    $("#lbl-lst-machineid").click(function(){
        $("#lst-machineid").val('').trigger('change');
    });

    $("#lbl-lst-failuretype").click(function(){
        $("#sel-failuretype").val('').trigger('change');
    });

    $('#lst-machineid, #lst-datemode, #lst_date_range').on("change", function() {
        // if (prod_opr == 'Restricted' && user_level == 'Operator'){
        //     var postedby = current_user;
        // }else{
        //     var postedby = '';
        // }

        // let technician = $("#txt-generated").val();

        // alert(postedby);

        let machineid = $("#lst-machineid").val();
        if (machineid == null){
            machineid = '';
        }
        let datemode = $("#lst-datemode").val();
        
        var date_range = $("#lst_date_range").val();
        if (date_range != ''){
            var start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
            var end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
        } else {
            var start_date = '';
            var end_date = '';
        }
        
        var data = new FormData();
        data.append("machineid", machineid);
        data.append("datemode", datemode);
        data.append("start_date", start_date);
        data.append("end_date", end_date);
        // data.append("technician", technician);
        
        $.ajax({
            url: "ajax/technician_list.ajax.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(answer) {
                $(".technicianListTable").DataTable().clear();   
                for (var i = 0; i < answer.length; i++) {
                    var mt = answer[i];
    
                    var datereported = mt.datereported;
                    var inctime = mt.inctime;
                    var inccode = mt.inccode;
                    var controlnum = mt.controlnum;
                    var machinedesc = mt.machinedesc;
                    var phase = mt.phase;
                    var curstatus = mt.curstatus;
                    var technician = mt.technician;
                    // var failuretype = mt.failuretype;
                    // var datecompleted = mt.datecompleted;
                    // var timeduration = mt.timeduration;

                    // if (Number(timeduration) == 0.00){
                    //     timeduration = '';
                    // }
                
                    tlst.row.add([datereported, inctime, inccode, machinedesc, phase, curstatus, technician])
                }
                tlst.draw();
            },
            beforeSend: function() {},
            complete: function() {
                $(".technicianListTable td").css({
                    "padding-top": "5px",
                    "padding-bottom": "5px"
                });
            }
        });
    });    

    $(".technicianListTable").on("draw.dt", function () {
        $(".technicianListTable td").css({
            "padding-top": "5px",
            "padding-bottom": "5px"
        });
    });

    let tech_access = $("#tech_access").val();
    if (tech_access == 'Full'){
        $('#modal-search-technicianlist').modal('show');
    }
});