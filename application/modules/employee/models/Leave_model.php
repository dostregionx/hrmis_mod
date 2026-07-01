<?php
/**
Purpose of file:    Model for Leave
Author:             Rose Anne L. Grefaldeo
System Name:        Human Resource Management Information System Version 10
Copyright Notice:   Copyright(C)2018 by the DOST Central Office - Information Technology Division
**/
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leave_model extends CI_Model {

        var $table = 'tblemprequest';
        var $tableid = 'requestID';

        function __construct()
        {
                $this->load->database();
                $this->db->initialize();
        }

        function getData($reqid='')
        {
                if($reqid!=''):
                        $res = $this->db->get_where('tblemprequest',array('requestID' => $reqid))->result_array();
                        return count($res) > 0 ? $res[0] : array();
                else:
                        return $this->db->get('tblemprequest')->result_array();
                endif;

        }

        function getall_request($empno = '')
        {
                $this->db->select('e.*, l.leaveType, CONCAT(e.requestDetails, ";", l.leaveType) AS updatedRequestDetails');
                $this->db->from('tblemprequest e');
                $this->db->join('tblleave l', 'LEFT(e.requestDetails, LOCATE(";", e.requestDetails) - 1) = l.leaveCode', 'left');

                if ($empno != '') {
                        $this->db->where('e.empNumber', $empno);
                }

                $this->db->where('e.requestCode', 'Leave');
                $this->db->order_by('e.requestID', 'DESC');

                return $this->db->get()->result_array();
        }

        public function getLatestBalance($strEmpNum)
        {
                $this->db->where("empNumber",$strEmpNum);
                $this->db->order_by('lb_id DESC');
                $res = $this->db->get('tblempleavebalance')->result_array();
                return count($res) > 0 ? $res[0] : array();
        }

        function add_employeeLeave($arrData)
        {
                $this->db->insert('tblempleave', $arrData);
                return $this->db->insert_id();
        }

        function add_leave_request($arrData)
        {
                $this->db->insert('tblemprequest', $arrData);
                return $this->db->insert_id();
        }

        function getLeaveByCode($code)
        {
                $res = $this->db->get_where('tblleave',array('leaveCode' => $code))->result_array();
                return count($res) > 0 ? $res[0] : array();
        }

        function checkExist($strDay = '')
        {
                $strSQL = " SELECT * FROM tblemprequest
                                        WHERE
                                        requestDetails ='$strDay'
                                        ";
                $objQuery = $this->db->query($strSQL);
                return $objQuery->result_array();
        }

        function save($arrData, $intReqId)
        {
                $this->db->where('requestID', $intReqId);
                $this->db->update('tblemprequest', $arrData);
                return $this->db->affected_rows()>0?TRUE:FALSE;
        }

        function delete($intReqId)
        {
                $this->db->where('requestID', $intReqId);
                $this->db->delete('tblemprequest');
                return $this->db->affected_rows()>0?TRUE:FALSE;
        }

        function getleave_balance($empid, $month=0, $yr=0)
        {
                $arrcond = array('empNumber' => $empid);

                if($month != 0) : $arrcond['periodMonth']=$month; endif;
                if($yr != 0) : $arrcond['periodYear']=$yr; endif;

                $this->db->order_by('lb_id,periodMonth, periodYear' , 'ASC');
                return $this->db->get_where('tblempleavebalance', $arrcond)->result_array();
        }

        function att_getleave_balance($month,$yr)
        {
                $this->db->where('periodMonth', $month);
                $this->db->where('periodYear', $yr);
                return $this->db->get('tblempleavebalance')->result_array();
        }

        # get Leaves
        function getleave($empid,$datefrom='',$dateto='')
        {
                $this->db->join('tblleave','tblleave.leaveCode = tblempleave.leaveCode');
                $this->db->where('empNumber', $empid);
                if($datefrom!='' && $dateto!=''):
                        $this->db->where("(leaveFrom between '".$datefrom."' and '".$dateto."' or leaveTo between '".$datefrom."' and '".$dateto."')");
                endif;
                return $this->db->get('tblempleave')->result_array();
        }

        function getleave2($empid,$period,$year)
        {
                $this->db->where('empNumber', $empid);
                $this->db->where('periodYear', $year);
                $this->db->order_by('lb_id', 'ASC');

                return $this->db->get('tblempleavebalance')->result_array();
        }

        # add leave
        function addLeaveBalance($arrData)
        {
                $this->db->insert('tblempleavebalance', $arrData);
                return $this->db->insert_id();
        }

        # update leave balance
        function editLeaveBalance($arrData, $lb_id)
        {
                $this->db->where('lb_id', $lb_id);
                $this->db->update('tblempleavebalance', $arrData);
                return $this->db->affected_rows()>0?TRUE:FALSE;
        }

        # delete leave
        function deleteLeaveBalance($lb_id)
        {
                $this->db->where('lb_id', $lb_id);
                $this->db->delete('tblempleavebalance');
                return $this->db->affected_rows()>0?TRUE:FALSE;
        }

        # Late undertime equivalent
        function ltut_table_equiv($ltut)
        {
                $arrequiv = array("0"  => 0.000, "1"  => 0.002, "2"  => 0.004,"3"  => 0.006,"4"  => 0.008,"5"  => 0.010,
                                                  "6"  => 0.012, "7"  => 0.015, "8"  => 0.017,"9"  => 0.019,"10" => 0.021,
                                                  "11" => 0.023, "12" => 0.025, "13" => 0.027,"14" => 0.029,"15" => 0.031,
                                                  "16" => 0.033, "17" => 0.035, "18" => 0.037,"19" => 0.040,"20" => 0.042,
                                                  "21" => 0.044, "22" => 0.046, "23" => 0.048,"24" => 0.050,"25" => 0.052,
                                                  "26" => 0.054, "27" => 0.056, "28" => 0.058,"29" => 0.060,"30" => 0.062,
                                                  "31" => 0.065, "32" => 0.067, "33" => 0.069,"34" => 0.071,"35" => 0.073,
                                                  "36" => 0.075, "37" => 0.077, "38" => 0.079,"39" => 0.081,"40" => 0.083,
                                                  "41" => 0.085, "42" => 0.087, "43" => 0.090,"44" => 0.092,"45" => 0.094,
                                                  "46" => 0.096, "47" => 0.098, "48" => 0.100,"49" => 0.102,"50" => 0.104,
                                                  "51" => 0.106, "52" => 0.108, "53" => 0.110,"54" => 0.112,"55" => 0.115,
                                                  "56" => 0.117, "57" => 0.119, "58" => 0.121,"59" => 0.123,"60" => 0.125);
                $hrs = 0;
                $mins = 0;
                if($ltut > 0):
                        if($ltut>=60){
                                $hrs=(int)($ltut/60);
                                $mins=($ltut%60);
                                $ltut = ($hrs*0.125) + ($arrequiv[$mins]);
                        }else{
                                $mins=($ltut%60);
                                $ltut = $arrequiv[$mins];
                        }
                endif;
                return $ltut;
        }

        # Leave Earned
        function leave_earned($absents)
        {
            $arraycredits = array("0.00"=>1.250,"0.50"=>1.229,"1.00"=>1.208,"1.50"=>1.188,
                              "2.00"=>1.167,"2.50"=>1.146,"3.00"=>1.125,"3.50"=>1.104,
                              "4.00"=>1.083,"4.50"=>1.063,"5.00"=>1.042,"5.50"=>1.021,
                              "6.00"=>1.000,"6.50"=>0.979,"7.00"=>0.958,"7.50"=>0.938,
                              "8.00"=>0.917,"8.50"=>0.896,"9.00"=>0.875,"9.50"=>0.854,
                              "10.00"=>0.833,"10.50"=>0.813,"11.00"=>0.792,"11.50"=>0.771,
                              "12.00"=>0.750,"12.50"=>0.729,"13.00"=>0.708,"13.50"=>0.687,
                              "14.00"=>0.667,"14.50"=>0.646,"15.00"=>0.625,"15.50"=>0.604,
                              "16.00"=>0.583,"16.50"=>0.562,"17.00"=>0.542,"17.50"=>0.521,
                              "18.00"=>0.500,"18.50"=>0.479,"19.00"=>0.458,"19.50"=>0.437,
                              "20.00"=>0.417,"20.50"=>0.396,"21.00"=>0.375,"21.50"=>0.354,
                              "22.00"=>0.333,"22.50"=>0.312,"23.00"=>0.292,"23.50"=>0.271,
                              "24.00"=>0.250,"24.50"=>0.229,"25.00"=>0.208,"25.50"=>0.187,
                              "26.00"=>0.167,"26.50"=>0.146,"27.00"=>0.125,"27.50"=>0.104,
                              "28.00"=>0.083,"28.50"=>0.062,"29.00"=>0.042,"29.50"=>0.021,
                              "30.00"=>0.000);
            $daysabsent = $absents.".00";

            return $arraycredits[$daysabsent];
        }

        public function update_empleave_balance_from_leave($params) {
                $sql = "CALL update_empleave_balance_from_leave(?, ?, ?, ?, ?, ?, ?)";
                $query = $this->db->query($sql, array(
                        $params['leaveid'],
                        $params['empNumber'],
                        $params['periodMonth'],
                        $params['periodYear'],
                        $params['leaveCode'],
                        $params['wpay'],
                        $params['wopay']
                ));
                return $query ? true : false;
        }

        /* ============================================================
         * NEW: Generic leave cap/credit enforcement (ALL leave types)
         * ============================================================ */

        public function get_leave_type_limit($leaveCode)
        {
                $leaveCode = strtoupper(trim($leaveCode));
                $row = $this->db->get_where('tblleave', array('leaveCode' => $leaveCode))->row_array();
                if (!$row) return 0;

                if (isset($row['numOfDays'])) return (int)$row['numOfDays'];
                if (isset($row['numberOfDays'])) return (int)$row['numberOfDays'];
                if (isset($row['numdays'])) return (int)$row['numdays'];
                return 0;
        }

        private function sum_approved_days_year($empNo, $leaveCode, $year)
        {
                $empNo = trim($empNo);
                $leaveCode = strtoupper(trim($leaveCode));
                $year = (int)$year;

                $this->db->where('empNumber', $empNo);
                $this->db->where('leaveCode', $leaveCode);
                $this->db->where('certifyHR', 'Y');
                $this->db->where('YEAR(leaveFrom) =', $year, false);

                $rows = $this->db->get('tblempleave')->result_array();

                $days = 0.0;
                foreach($rows as $leave):
                        //$dates = dateRange($leave['leaveFrom'],$leave['leaveTo']);
			$dates = $this->dateRange($leave['leaveFrom'], $leave['leaveTo']);
                        $days += (float)count($dates);
                endforeach;

                return $days;
        }

        private function sum_pending_days_year_from_requests($empNo, $leaveCode, $year, $excludeReqId = null)
        {
                $empNo = trim($empNo);
                $leaveCode = strtoupper(trim($leaveCode));
                $year = (int)$year;

                $this->db->select('COALESCE(SUM(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(e.requestDetails, ";", 4), ";", -1) AS DECIMAL(10,3))),0) AS total', false);
                $this->db->from('tblemprequest e');
                $this->db->where('e.empNumber', $empNo);
                $this->db->where('e.requestCode', 'Leave');
                $this->db->where('e.requestStatus', 'Filed Request');

                // ✅ FIX: escape leaveCode properly so it becomes ='WL'
                $this->db->where(
                        'UPPER(LEFT(e.requestDetails, LOCATE(";", e.requestDetails) - 1)) = '.$this->db->escape($leaveCode),
                        null,
                        false
                );

                // YEAR(...) = 2026 is OK without quotes
                $this->db->where(
                        'YEAR(SUBSTRING_INDEX(SUBSTRING_INDEX(e.requestDetails, ";", 2), ";", -1)) = '.$year,
                        null,
                        false
                );

                if (!empty($excludeReqId)) {
                        $this->db->where('e.requestID !=', $excludeReqId);
                }

                $row = $this->db->get()->row_array();
                return $row ? (float)$row['total'] : 0.0;
        }

        public function validate_leave_request_limits($empNo, $leaveCode, $leaveFrom, $leaveTo, $daysApplied, $excludeReqId = null)
        {
                $empNo = trim($empNo);
                $leaveCode = strtoupper(trim($leaveCode));
                $daysApplied = (float)$daysApplied;

                if ($leaveCode === '' || $leaveFrom === '' || $leaveTo === '') {
                        return array('ok' => false, 'msg' => 'Leave type and dates are required.');
                }

                if ($daysApplied <= 0) {
                        return array('ok' => false, 'msg' => 'Invalid number of days applied.');
                }

                $year = (int)date('Y', strtotime($leaveFrom));

                // VL/SL: credit-based check
                if ($leaveCode === 'VL' || $leaveCode === 'SL') {
                        $bal = $this->getLatestBalance($empNo);
                        if (!$bal) {
                                return array('ok' => false, 'msg' => 'No set leave balance. Please contact HR Unit.');
                        }

                        $available = 0.0;
                        if ($leaveCode === 'VL') $available = isset($bal['vlBalance']) ? (float)$bal['vlBalance'] : 0.0;
                        if ($leaveCode === 'SL') $available = isset($bal['slBalance']) ? (float)$bal['slBalance'] : 0.0;

                        if ($daysApplied > $available + 1e-9) {
                                return array(
                                        'ok' => false,
                                        'msg' => $leaveCode.' credits insufficient. Available: '.$available.' day(s). Requested: '.$daysApplied.' day(s).'
                                );
                        }
                        return array('ok' => true, 'msg' => '');
                }

                // Other leave types: annual cap if configured
                $limit = (int)$this->get_leave_type_limit($leaveCode);

                if ($limit > 0) {
                        $approved = $this->sum_approved_days_year($empNo, $leaveCode, $year);
                        $pending  = $this->sum_pending_days_year_from_requests($empNo, $leaveCode, $year, $excludeReqId);

                        $wouldTotal = $approved + $pending + $daysApplied;

                        if ($wouldTotal > $limit + 1e-9) {
                                return array(
                                        'ok' => false,
                                        'msg' => $leaveCode.' limit exceeded for '.$year.'. Allowed: '.$limit.
                                                 ' day(s). Approved: '.$approved.
                                                 ' day(s), Pending: '.$pending.
                                                 ' day(s), This request: '.$daysApplied.' day(s).'
                                );
                        }
                }

                return array('ok' => true, 'msg' => '');
        }

	public function approved_leave($empid, $datefrom, $dateto, $leaveCode)
	{
	        $this->db->where('empNumber', $empid);
	        $this->db->where('leaveCode', strtoupper(trim($leaveCode)));
	        $this->db->where('certifyHR', 'Y');
	        $this->db->where("(leaveFrom <= '".$dateto."' AND leaveTo >= '".$datefrom."')");

	        $rows = $this->db->get('tblempleave')->result_array();

	        $total = 0.000;

	        foreach ($rows as $leave):
	                //$dates = dateRange($leave['leaveFrom'], $leave['leaveTo']);
			$dates = $this->dateRange($leave['leaveFrom'], $leave['leaveTo']);

	                foreach ($dates as $d):
	                        if ($d >= $datefrom && $d <= $dateto):
	                                $day = date('D', strtotime($d));
	                                if (!in_array($day, array('Sat', 'Sun'))):
	                                        $total += 1;
	                                endif;
	                        endif;
	                endforeach;
	        endforeach;

	        return $total;
	}

	private function dateRange($start, $end)
	{
        	$dates = array();

        	$current = strtotime($start);
        	$end = strtotime($end);

        	while ($current <= $end) {
                	$dates[] = date('Y-m-d', $current);
                	$current = strtotime('+1 day', $current);
        	}

        	return $dates;
	}
}
