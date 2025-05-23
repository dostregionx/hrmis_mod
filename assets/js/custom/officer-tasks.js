function getrequest_status(stat)
{
    if(stat.toLowerCase() == "recommended" || stat.toLowerCase() == "recommend"){
        return "Recommend";
    }
    else if(stat.toLowerCase() == "approved" || stat.toLowerCase() == "approve"){
        return "Approve";
    }
    else if(stat.toLowerCase() == 'certified'){
        return 'Certify';
    }
}

$(document).ready(function() {
    $('#table-notif').dataTable( {
        "initComplete": function(settings, json) {
            $('.loading-image').hide();
            $('#table-notif').css('visibility', 'visible');
        },"columnDefs": [{ "orderable":false, "targets":'no-sort' }]} );

    $('#table-notif').on('click', 'a#btnview-details', function() {
    });

    $('#printreport').click(function(){
        
        });

});

function view_details(x){
    var data = $(x).data('json');
        var status = data['req_nextsign'].split(';');

        var details = data['req_details'].split(';');
        $('.modal-title').html('<b>'+data['req_code']+'</b>');
        if(data['req_code'] == "Leave") {
            $('.txtreq_patient').hide();
            $('.txtreq_location').show();
            
            if(details[0] == 'SL'){
                $('.txtreq_patient').show();
            }

            if($.inArray(details[0],['PTL','FL','PL','SL','STL','MTL']) != -1){
                $('.txtreq_location').hide();
            }
            $('#txtleave_id').val(data['req_id']);
            $('#xtxtleave_id').val(data['req_id']);
            $('#txtreq_empno').val(data['req_emp']);
            $('#txtreq_empname').val(data['req_empname']);
            $('#txtreq_leave_type').val(details[0]);
            $('#txtreq_dfrom').val(details[1]);
            $('#txtreq_dto').val(details[2]);
            $('#txtreq_reason').val(details[6]);
            $('#txtreq_patient').val(details[0] == 'SL' ? (details[7] == 'out patient' ? 'Out' : 'In') + ' Patient' : '');
            // $('#txtreq_location').val(details[0] == 'VL' ? details[6] == 'local' ? 'Within-the-country' : 'Abroad' : '');
            $('#txtreq_location').val(details[0] == 'VL' ? details[8] == 'within the country' ? 'Within the country' : 'Abroad' : '');
            $("#txtreq_comm").val(details[13]);
            $("#txtreq_comm").css('text-transform', 'capitalize');
            $("#txtreq_reason").css('text-transform', 'capitalize');

            console.log(details);

            if (data['req_sign_no'] == 'Signatory1') {
                $("#approvedfor_container").show();
                $('#txtdayswpay').val(details[3]);
            }else{
                $("#approvedfor_container").show();
                $("#approvedfor_container input").attr('disabled','disabled');

                $('#txtdayswpay').val(details[14]);
                $('#txtdayswopay').val(details[15]);
                $('#txtdayspayothers').val(details[16]);
            }



            $('select#selreq_stat').empty().append('<option value="'+status[0]+'">'+getrequest_status(status[0])+'</option>');
            $('select#selreq_stat').append('<option value="Disapproved">Disapprove</option>');
            $('select#selreq_stat').selectpicker('refresh');
            
            if(data['req_status'] == 'Cancelled'){
                $('#btncertify').hide();
                $('select#selreq_stat,#txtreq_location,#txtreq_comm,#txtreq_remarks').attr('disabled','disabled');
            }else{
                $('#btncertify').show();
                // $('select#selreq_stat,#txtreq_location,#txtreq_comm,#txtreq_remarks').removeAttr('disabled');
                $('#txtreq_location,#txtreq_comm,#txtreq_patient').attr('disabled','disabled');
                $('select#selreq_stat').selectpicker('refresh');
            }
            $('#request_leave').modal('show');
        }

        if(data['req_code'] == "OB") {
            $('#txtob_id').val(data['req_id']);
            $('#xtxtob_id').val(data['req_id']);
            $('#txtob_empno').val(data['req_emp']);
            $('#txtob_empname').val(data['req_empname']);
            $('#txtob_type').val(details[0]);
            $('#txtob_place').val(details[6]);
            $('#txtob_purpose').val(details[7]);
            if(details[9] == 'Y'){
                $('#txtob_wmeal').prop('checked',true);
            }else{
                $('#txtob_wmeal').prop('checked',false);
            }
            $('#txtob_dfrom').val(details[2]);
            $('#txtob_dto').val(details[3]);
            $('#txtob_time_in').val(details[4]);
            $('#txtob_time_out').val(details[5]);

            $('select#selob_stat').empty().append('<option value="'+status[0]+'">'+getrequest_status(status[0])+'</option>');
            $('select#selob_stat').append('<option value="Disapproved">Disapprove</option>');
            $('select#selob_stat').selectpicker('refresh');

            $('#request_ob').modal('show');
        }

        if(data['req_code'] == "TO") {
            $('#txtto_id').val(data['req_id']);
            $('#xtxtto_id').val(data['req_id']);
            $('#txtto_empno').val(data['req_emp']);
            $('#txtto_empname').val(data['req_empname']);
            $('#txtto_desti').val(details[0]);
            $('#txtto_purpose').val(details[3]);
            if(details[7] == 'Y'){
                $('#txtto_wmeal').prop('checked',true);
            }else{
                $('#txtto_wmeal').prop('checked',false);
            }
            $('#txtto_dfrom').val(details[1]);
            $('#txtto_dto').val(details[2]);
            $('select#selto_stat').empty().append('<option value="'+status[0]+'">'+getrequest_status(status[0])+'</option>');
            $('select#selto_stat').append('<option value="Disapproved">Disapprove</option>');
            $('select#selto_stat').selectpicker('refresh');

            $('#request_to').modal('show');
        }
}


function print_report(x){
    var requestid = $(x).data('requestid');
    var requesttype = $(x).data('requesttype');

        if (requesttype == "Leave") {
            var link = baseUrl+"employee/reports/generate/?rpt=reportLeave&req_id="+requestid;
        }

        if (requesttype == "OB") {
            var link = baseUrl+"employee/reports/generate/?rpt=reportOB&req_id="+requestid;
        }

        if (requesttype == "TO") {
            var link = baseUrl+"employee/reports/generate/?rpt=reportTO&req_id="+requestid;
        }



        $('#leave-embed').attr('src',link);
        $('#leave-embed-fullview').attr('href',link);
        $('#leave-form').modal('show');
}
