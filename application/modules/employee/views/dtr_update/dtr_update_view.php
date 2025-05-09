<?php
/** 
Purpose of file:    DTR Update View
Author:             Rose Anne L. Grefaldeo
System Name:        Human Resource Management Information System Version 10
Copyright Notice:   Copyright(C)2018 by the DOST Central Office - Information Technology Division
**/
$month = isset($_GET['month']) ? $_GET['month'] : date('F');
$form_action = $action == 'add' ? 'employee/update_dtr/submit' : 'employee/update_dtr/edit?req_id=' . $_GET['req_id'];
$hrmodule = isset($_GET['module']) ? ($_GET['module'] == 'hr' ? 1 : 0) : 0;
?>
<!-- BEGIN PAGE BAR -->
<?= load_plugin('css', ['datepicker', 'timepicker', 'select', 'select2']) ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?= base_url('home') ?>">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="javascript:;">Request</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span><?= ucwords($hrmodule ? 'view' : $action) ?> DTR Update</span>
        </li>
    </ul>
</div>
<!-- END PAGE BAR -->
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
       &nbsp;
    </div>
</div>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase">DTR Update</span>
                </div>
            </div>
            <div class="portlet-body">
            <?= form_open($form_action, ['method' => 'post', 'id' => 'frmDTRupdate']) ?>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label">Date : <span class="required"> * </span></label>
                        <div class="input-icon right">
                            <input class="form-control date-picker" name="dtmDTRupdate" id="dtmDTRupdate" type="text"
                                value="<?= isset($new_dtr_details[1]) ? ($new_dtr_details[1] == '00:00:00' ? '12:00:00 AM' : $new_dtr_details[1]) : '' ?>" data-date-format="yyyy-mm-dd" autocomplete="off" <?= $hrmodule ? 'disabled' : '' ?>>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>For the Month of : </label>
                            <select class="bs-select form-control" name="dtmMonthOf" id="dtmMonthOf" <?= $hrmodule ? 'disabled' : '' ?>>
                                <option value=""> </option>
                                <?php foreach (range(1, 12) as $m): ?>
                                    <option value="<?= sprintf('%02d', $m) ?>"
                                        <?php if (isset($new_dtr_details[33])):
                                            echo $m == sprintf('%02d', $new_dtr_details[33]) ? 'selected' : '';
                                        else:
                                            echo $m == sprintf('%02d', date('m')) ? 'selected' : '';
                                        endif; ?> >
                                        <?= date('F', mktime(0, 0, 0, $m, 10)) ?></option>
                                <?php endforeach; ?>
                            </select>
                    </div>
                </div>
            </div> -->
            <div class="row"> 
                <div class="col-sm-4">
                    <label class="control-label bold">Old Timein/Timeout : </label>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Morning In : </label>
                                <input name="strOldMorningIn" id="strOldMorningIn" type="time" size="20" maxlength="20" class="form-control"
                                        value="<?= isset($old_dtr_details['inAM']) ? $old_dtr_details['inAM'] : '' ?>" autocomplete="off" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Morning Out :</label>
                                <input name="strOldMorningOut" id="strOldMorningOut" type="time" size="20" maxlength="20" class="form-control" 
                                        value="<?= isset($old_dtr_details['outAM']) ? $old_dtr_details['outAM'] : '' ?>" autocomplete="off" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Afternoon In :</label>
                                <input name="strOldAfternoonIn" id="strOldAfternoonIn" type="time" size="20" maxlength="20" class="form-control" 
                                        value="<?= isset($old_dtr_details['inPM']) ? $old_dtr_details['inPM'] : '' ?>" autocomplete="off" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Afternoon Out :</label>
                                <input name="strOldAfternoonOut" id="strOldAfternoonOut" type="time" size="20" maxlength="20" class="form-control" 
                                        value="<?= isset($old_dtr_details['outPM']) ? $old_dtr_details['outPM'] : '' ?>" autocomplete="off" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Overtime In : </label>
                                <input name="strOldOvertimeIn" id="strOldOvertimeIn" type="time" size="20" maxlength="20" class="form-control" 
                                        value="<?= isset($old_dtr_details['inOT']) ? $old_dtr_details['inOT'] : '' ?>" autocomplete="off" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Overtime Out : </label>
                                <input name="strOldOvertimeOut" id="strOldOvertimeOut" type="time" size="20" maxlength="20" class="form-control" 
                                        value="<?= isset($old_dtr_details['outOT']) ? $old_dtr_details['outOT'] : '' ?>" autocomplete="off" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <label class="control-label bold">New Timein/Timeout : </label>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Morning Time In : </label>
                                <input type="time" step="any" class="form-control" name="dtmMorningIn" id="dtmMorningIn" 
                                        value="<?= isset($new_dtr_details[2]) ? ($new_dtr_details[2] == '00:00:00' ? '12:00:00 AM' : $new_dtr_details[2]) : '' ?>" autocomplete="off" <?= $hrmodule ? 'disabled' : '' ?>> 
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                 <label class="control-label">Morning Time Out :</label>
                                <input type="time" step="any" class="form-control" name="dtmMorningOut" id="dtmMorningOut" 
                                        value="<?= isset($new_dtr_details[3]) ? ($new_dtr_details[3] == '00:00:00' ? '12:00:00 AM' : $new_dtr_details[3]) : '' ?>" autocomplete="off" <?= $hrmodule ? 'disabled' : '' ?>>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Afternoon Time In :</label>
                                <input type="time" step="any" class="form-control" name="dtmAfternoonIn" id="dtmAfternoonIn" 
                                        value="<?= isset($new_dtr_details[4]) ? ($new_dtr_details[4] == '00:00:00' ? '12:00:00 AM' : $new_dtr_details[4]) : '' ?>" autocomplete="off" <?= $hrmodule ? 'disabled' : '' ?>>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Afternoon Time Out :</label>
                                <input type="time" step="any" class="form-control" name="dtmAfternoonOut" id="dtmAfternoonOut" 
                                        value="<?= isset($new_dtr_details[5]) ? ($new_dtr_details[5] == '00:00:00' ? '12:00:00 AM' : $new_dtr_details[5]) : '' ?>" autocomplete="off" <?= $hrmodule ? 'disabled' : '' ?>>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Overtime In :</label>
                                <input type="time" step="any" class="form-control" name="dtmOvertimeIn" id="dtmOvertimeIn" 
                                    value="<?= isset($new_dtr_details[6]) ? ($new_dtr_details[6] == '00:00:00' ? $new_dtr_details[5] : $new_dtr_details[6]) : '' ?>" autocomplete="off" <?= $hrmodule ? 'disabled' : '' ?>>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Overtime Out :</label>
                                <input type="time" step="any" class="form-control" name="dtmOvertimeOut" id="dtmOvertimeOut" 
                                        value="<?= isset($new_dtr_details[7]) ? ($new_dtr_details[7] == '00:00:00' ? $new_dtr_details[5] : $new_dtr_details[7]) : '' ?>" autocomplete="off" <?= $hrmodule ? 'disabled' : '' ?>>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label class="control-label">Reason :</label>
                              <textarea name="strReason" id="strReason" type="text" size="20" maxlength="100" class="form-control" <?= $hrmodule ? 'disabled' : '' ?>><?= isset($new_dtr_details[32]) ? $new_dtr_details[32] : '' ?></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label class="control-label">Supporting Evidence :</label>
                            <textarea name="strEvidence" id="strEvidence" type="text" size="20" maxlength="100" class="form-control" <?= $hrmodule ? 'disabled' : '' ?>><?= isset($new_dtr_details[34])
    ? $new_dtr_details[34]
    : '' ?></textarea>
                    </div>
                </div>
            </div>
            <div class="row" id="signatory1_textbox">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label class="control-label">Authorized Official (Signatory) :</label>
                            <select name="strSignatory" id="strSignatory" type="text" class="form-control select2 form-required" <?= $hrmodule ? 'disabled' : '' ?>>
                                    <option value="0">-- SELECT SIGNATORY --</option>
                                    <?php foreach ($arrEmployees as $i => $data):
                                        $selected = isset($new_dtr_details[35]) ? ($new_dtr_details[35] == $data['empNumber'] ? 'selected' : '') : ''; ?>
                                            <option value="<?= $data['empNumber'] ?>" <?= $selected ?>><?= ucfirst($data['surname']) . ', ' . $data['firstname'] . ' ' . $data['middleInitial'] . ' ' . $data['nameExtension'] ?></option>
                                        <?php
                                    endforeach; ?>
                            </select>
                    </div>
                </div>
            </div>
            <div class="row"><div class="col-sm-8"><hr></div></div>
            <div class="row">
                <div class="col-sm-8">
                    <?php if (!$hrmodule): ?>
                        <button type="submit" class="btn btn-success" id="btn-request-dtr">
                            <i class="icon-check"></i>
                            <?= $this->uri->segment(3) == 'edit' ? 'Save' : 'Submit' ?></button>
                        <a href="<?= base_url('employee/update_dtr') ?>" class="btn blue"> <i class="icon-ban"></i> Cancel</a>
                    <?php else: ?>
                        <a href="<?= base_url('hr/request?request=dtr') ?>" class="btn blue"> <i class="icon-ban"></i> Cancel</a>
                    <?php endif; ?>
                    <button type="button" id="printreport" value="reportDTRupdate" class="btn grey-cascade pull-right"><i class="icon-magnifier"></i> Print/Preview</button>
                </div>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<!-- begin dtr form modal -->
<div id="dtr-form" class="modal fade" aria-hidden="true">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title bold">Confirmation Slip</h4>
            </div>
            <div class="modal-body">
                <div class="row form-body">
                    <div class="col-md-12">
                        <div class="form-group">
                            <embed src="" id="dtr-embed" frameborder="0" width="100%" height="500px">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="" id="dtr-embed-fullview" class="btn blue btn-sm" target="_blank"> <i class="glyphicon glyphicon-resize-full"> </i> Open in New Tab</a>
                <button type="button" class="btn dark btn-sm" data-dismiss="modal"> <i class="icon-ban"> </i> Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end dtr form modal -->

<?= load_plugin('js', ['form_validation', 'datepicker', 'select', 'select2', 'timepicker']) ?>
<script>
$(document).ready(function() {
    
    $('.date-picker').datepicker();
    $('.date-picker').on('changeDate', function(){
        $(this).datepicker('hide');
    });

    $('.timepicker').timepicker({
        timeFormat: 'HH:mm:ss A',
        minuteStep: 1,
        secondStep: 1,
        disableFocus: true,
        showInputs: false,
        showSeconds: true,
        showMeridian: true,
        defaultTime: '12:00:00 a'
    });


    $('#dtmDTRupdate').change(function() {
        $date=$('#dtmDTRupdate').val();
        $.ajax({
             url: "<?= base_url('employee/update_dtr') ?>/getinout?date="+$date,
        success: function(result){
            $arrTime = $.trim(result).split(';');
            $('input[name="strOldMorningIn"],input[name="dtmMorningIn"]').val($arrTime[0]);
            $('input[name="strOldMorningOut"],input[name="dtmMorningOut"]').val($arrTime[1]);
            $('input[name="strOldAfternoonIn"],input[name="dtmAfternoonIn"]').val($arrTime[2]);
            $('input[name="strOldAfternoonOut"],input[name="dtmAfternoonOut"]').val($arrTime[3]);
            $('input[name="strOldOvertimeIn"],input[name="dtmOvertimeIn"]').val($arrTime[4]);
            $('input[name="strOldOvertimeOut"],input[name="dtmOvertimeOut"]').val($arrTime[5]);
        }});
    });

    $('#dtmDTRupdate').on('keyup keypress change',function() {
        check_null('#dtmDTRupdate','Date must not be empty.');
    });

    $('#btn-request-dtr').click(function(e) {
        if(check_null('#dtmDTRupdate','Date must not be empty.') > 0){
            e.preventDefault();
        }
    });

    $('#printreport').click(function(){
        var dtrupdate=$('#dtmDTRupdate').val();
        var oldmorin=$('#strOldMorningIn').val();
        var oldmorout=$('#strOldMorningOut').val();
        var oldafin=$('#strOldAfternoonIn').val();
        var oldaftout=$('#strOldAfternoonOut').val();
        var oldOTin=$('#strOldOvertimeIn').val();
        var oldOTout=$('#strOldOvertimeOut').val();
        var morningin=$('#dtmMorningIn').val();
        var morningout=$('#dtmMorningOut').val();
        var aftnoonin=$('#dtmAfternoonIn').val();
        var aftnoonout=$('#dtmAfternoonOut').val();
        var OTtimein=$('#dtmOvertimeIn').val();
        var OTtimeout=$('#dtmOvertimeOut').val();
        var reason=$('#strReason').val();
        var month=$('#dtmMonthOf').val();
        var evidence=$('#strEvidence').val();
        var signatory=$('#strSignatory').val();
        
        var link = "<?= base_url(
            'employee/reports/generate/?rpt=reportDTRupdate'
        ) ?>"+"&dtrupdate="+dtrupdate+"&oldmorin="+oldmorin+"&oldmorout="+oldmorout+"&oldafin="+oldafin+"&oldaftout="+oldaftout+"&oldOTin="+oldOTin+"&oldOTout="+oldOTout+"&morningin="+morningin+"&morningout="+morningout+"&aftnoonin="+aftnoonin+"&aftnoonout="+aftnoonout+"&OTtimein="+OTtimein+"&OTtimeout="+OTtimeout+"&month="+month+"&evidence="+evidence+"&reason="+reason+"&signatory="+signatory;
        $('#dtr-embed').attr('src',link);
        $('#dtr-embed-fullview').attr('href',link);
        $('#dtr-form').modal('show');
    
    });
 });
</script>