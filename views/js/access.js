$('#modal-search-users').on('shown.bs.modal', function () {
   var tableUsers = $('.userRightsTable').DataTable();
   tableUsers.columns.adjust();
});

$(function() {
    // $('select').not('#sel-empid, #sel-utype').prop('disabled', true);
    initialize();

    $("#txt-username").val(generateTempUsername());
    $("#txt-upassword").val(generateTempPassword());

    $("#btn-new").click(function(){
        new_user_rights();
    });
    
    $("#btn-save").click(function(){
        save_user_rights();
    });

    $('#sel-utype').on('change', function () {
        const selected = $(this).val();
        if (selected === 'Manufacturing') {
            hideTechnical();
            showManufacturing();
            restrictAll();
        } else {
            hideManufacturing();
            showTechnical();
            restrictAll();
        }
    });

    $('.userRightsTable tbody').on('dblclick', 'tr', function () {
        var userid = $(this).attr("userId");
        var data = new FormData();
        data.append("userid", userid);
        $.ajax({
            url:"ajax/get_user_rights_record.ajax.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(answer){
                $("#txt-userid").val(answer["userid"]);
                $("#sel-empid").val(answer["empid"]).trigger('change');
                $("#sel-utype").val(answer["utype"]).trigger('change');
                $("#sel-ulevel").val(answer["ulevel"]).trigger('change');
                $("#sel-mmd").val(answer["mmd"]).trigger('change');
                $("#sel-mip").val(answer["mip"]).trigger('change');
                $("#sel-mfp").val(answer["mfp"]).trigger('change');
                $("#sel-mpc").val(answer["mpc"]).trigger('change');
                $("#sel-mrm").val(answer["mrm"]).trigger('change');
                $("#sel-mmr").val(answer["mmr"]).trigger('change');
                $("#sel-md").val(answer["md"]).trigger('change');
                $("#sel-mret").val(answer["mret"]).trigger('change');
                $("#sel-mir").val(answer["mir"]).trigger('change');
                $("#sel-minv").val(answer["minv"]).trigger('change');
                $("#sel-mrep").val(answer["mrep"]).trigger('change');
                $("#sel-mirm").val(answer["mirm"]).trigger('change');
                $("#sel-mifp").val(answer["mifp"]).trigger('change');
                $("#sel-mcrm").val(answer["mcrm"]).trigger('change');
                $("#sel-mcfg").val(answer["mcfg"]).trigger('change');
                $("#sel-mopr").val(answer["mopr"]).trigger('change');

                $("#sel-tmd").val(answer["tmd"]).trigger('change');
                $("#sel-tmt").val(answer["tmt"]).trigger('change');
                $("#sel-tmi").val(answer["tmi"]).trigger('change');
                $("#sel-tpo").val(answer["tpo"]).trigger('change');
                $("#sel-tis").val(answer["tis"]).trigger('change');
                $("#sel-trel").val(answer["trel"]).trigger('change');
                $("#sel-tret").val(answer["tret"]).trigger('change');
                $("#sel-tadj").val(answer["tadj"]).trigger('change');
                $("#sel-tinv").val(answer["tinv"]).trigger('change');
                $("#sel-trep").val(answer["trep"]).trigger('change');
                $("#sel-tprt").val(answer["tprt"]).trigger('change');
                $("#sel-tcat").val(answer["tcat"]).trigger('change');
                $("#sel-tbr").val(answer["tbr"]).trigger('change');
                $("#sel-tmac").val(answer["tmac"]).trigger('change');
                $("#sel-tcls").val(answer["tcls"]).trigger('change');

                $("#sel-psup").val(answer["psup"]).trigger('change');
                $("#sel-pemp").val(answer["pemp"]).trigger('change');
                $("#sel-paccess").val(answer["paccess"]).trigger('change');
                $("#sel-plog").val(answer["plog"]).trigger('change');
                $("#sel-pcost").val(answer["pcost"]).trigger('change');

                $("#trans_type").val("Update");
                $("#modal-search-users").modal('hide');
            }
        })
    });         

    function initialize(){
        $('#trans_type').val('New');
        $("#txt-userid").val('');

        $("#txt-username").val(generateTempUsername());
        $("#txt-upassword").val(generateTempPassword());
        $("#sel-empid").val('').trigger('change');
        $("#sel-utype").val('Manufacturing').trigger('change');
        $("#sel-ulevel").val('Regular').trigger('change');

        hideTechnical();
        showManufacturing();
        restrictAll();
    }

    function new_user_rights(){
        swal.fire({
           title: 'Do you want to create new user access rights?',
           type: 'question',
           showCancelButton: true,
           confirmButtonText: 'Yes, Create!',
           cancelButtonText: 'No',
           confirmButtonClass: 'btn btn-outline-success',
           cancelButtonClass: 'btn btn-outline-danger',
           allowOutsideClick: false,
           buttonsStyling: false
        }).then(function(result) {
            if(result.value) {  
              initialize();
            }
        }); 	
    }
    
    function save_user_rights(){
        let allRestricted = true;
        $('select').not('#sel-empid, #sel-utype').each(function () {
            if ($(this).val() !== 'Restricted') {
                allRestricted = false;
                return false;
            }
        });

        if (allRestricted === false){
            swal.fire({
                title: 'Do you want to save user access rights?',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, save!',
                cancelButtonText: 'No',
                confirmButtonClass: 'btn btn-outline-success',
                cancelButtonClass: 'btn btn-outline-danger',
                allowOutsideClick: false,
                buttonsStyling: false
            }).then(function(result) {
                if(result.value) {  
                    post_user_rights();
                }
            });
        }else{
            swal.fire({
                title: 'Incomplete data entry!',
                type: 'error',
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 1500
            })
        } 	
    } 

    function post_user_rights(){
        $("#btn-save").prop('disabled', true);  
        var trans_type = $("#trans_type").val(); 

        var userid = $("#txt-userid").val();
        var username = $("#txt-username").val();
        var upassword = $("#txt-upassword").val();

        var empid = $("#sel-empid").val();
        var utype = $("#sel-utype").val();
        var ulevel = $("#sel-ulevel").val();

        var mmd = $("#sel-mmd").val();
        var mip = $("#sel-mip").val();
        var mfp = $("#sel-mfp").val();
        var mpc = $("#sel-mpc").val();
        var mrm = $("#sel-mrm").val();
        var mmr = $("#sel-mmr").val();
        var md = $("#sel-md").val();
        var mret = $("#sel-mret").val();
        var mir = $("#sel-mir").val();
        var minv = $("#sel-minv").val();
        var mrep = $("#sel-mrep").val();
        var mirm = $("#sel-mirm").val();
        var mifp = $("#sel-mifp").val();
        var mcrm = $("#sel-mcrm").val();
        var mcfg = $("#sel-mcfg").val();
        var mopr = $("#sel-mopr").val();

        var tmd = $("#sel-tmd").val();
        var tmt = $("#sel-tmt").val();
        var tmi = $("#sel-tmi").val();
        var tpo = $("#sel-tpo").val();
        var tis = $("#sel-tis").val();
        var trel = $("#sel-trel").val();
        var tret = $("#sel-tret").val();
        var tadj = $("#sel-tadj").val();
        var tinv = $("#sel-tinv").val();
        var trep = $("#sel-trep").val();
        var tprt = $("#sel-tprt").val();
        var tcat = $("#sel-tcat").val();
        var tbr = $("#sel-tbr").val();
        var tmac = $("#sel-tmac").val();
        var tcls = $("#sel-tcls").val();  
        
        var psup = $("#sel-psup").val();
        var pemp = $("#sel-pemp").val();
        var paccess = $("#sel-paccess").val();  
        var plog = $("#sel-plog").val();
        var pcost = $("#sel-pcost").val();
              
        var user = new FormData();
        user.append("trans_type", trans_type);
        user.append("userid", userid);
        user.append("username", username);
        user.append("upassword", upassword);
        user.append("empid", empid);
        user.append("utype", utype);
        user.append("ulevel", ulevel);

        user.append("mmd", mmd);
        user.append("mip", mip);
        user.append("mfp", mfp);
        user.append("mpc", mpc);
        user.append("mrm", mrm);
        user.append("mmr", mmr);
        user.append("md", md);
        user.append("mret", mret);
        user.append("mir", mir);
        user.append("minv", minv);
        user.append("mrep", mrep);
        user.append("mirm", mirm);
        user.append("mifp", mifp);
        user.append("mcrm", mcrm);
        user.append("mcfg", mcfg);
        user.append("mopr", mopr);

        user.append("tmd", tmd);
        user.append("tmt", tmt);
        user.append("tmi", tmi);
        user.append("tpo", tpo);
        user.append("tis", tis);
        user.append("trel", trel);
        user.append("tret", tret);
        user.append("tadj", tadj);
        user.append("tinv", tinv);
        user.append("trep", trep);
        user.append("tprt", tprt);
        user.append("tcat", tcat);
        user.append("tbr", tbr);
        user.append("tmac", tmac);
        user.append("tcls", tcls);

        user.append("psup", psup);
        user.append("pemp", pemp);
        user.append("paccess", paccess);
        user.append("plog", plog);
        user.append("pcost", pcost);
    
        $.ajax({
           url:"ajax/user_rights_save_record.ajax.php",
           method: "POST",
           data: user,
           cache: false,
           contentType: false,
           processData: false,
           async: false,
           dataType:"text",
           success:function(answer){
              $("#btn-save").prop('disabled', false);                        
           },
           error: function () {
              alert("Oops. Something went wrong!");
           },
           complete: function () {
              swal.fire({
                 title: 'User access rights successfully saved!',
                 type: 'success',
                 allowOutsideClick: false,
                 showConfirmButton: false,
                 timer: 1500
              })
              if (trans_type == 'New'){
                window.open("extensions/tcpdf/pdf/tempcredential.php?username="+username+"&upassword="+upassword, "_blank"); 
              }
              //initialize();
              window.location = "access"; 
           }
        })   	
    }

    
    function restrictAll(){
        $('select').not('#sel-empid, #sel-utype').val('Restricted').trigger('change');
    }

    function hideManufacturing(){
        $('#m1').hide();
        $('#m2').hide();
        $('#m3').hide();
        $('#m4').hide();
        $('#m5').hide();
        $('#m6').hide();
    }

    function showManufacturing(){
        $('#m1').show();
        $('#m2').show();
        $('#m3').show();
        $('#m4').show();
        $('#m5').show();
        $('#m6').show();
    }    

    function hideTechnical(){
        $('#t1').hide();
        $('#t2').hide();
        $('#t3').hide();
        $('#t4').hide();
        $('#t5').hide();
    }
    
    function showTechnical(){
        $('#t1').show();
        $('#t2').show();
        $('#t3').show();
        $('#t4').show();
        $('#t5').show();
    }
    
    // Generate a temporary username (only letters and numbers)
    function generateTempUsername(length = 8) {
        const prefix = 'user';
        const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        const randomString = [...Array(length)].map(() => chars.charAt(Math.floor(Math.random() * chars.length))).join('');
        return prefix + randomString;
    }

    // Generate a temporary password (only letters and numbers)
    function generateTempPassword(length = 12) {
        const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let password = '';
        for (let i = 0; i < length; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return password;
    }
    
});