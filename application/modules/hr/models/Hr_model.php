<?php 
/** 
Purpose of file:    Model for HR update
Author:             Rose Anne L. Grefaldeo
System Name:        Human Resource Management Information System Version 10
Copyright Notice:   Copyright(C)2018 by the DOST Central Office - Information Technology Division
**/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Hr_model extends CI_Model {
	
	var $table = 'tblemppersonal';
	var $tableid = 'empNumber';

	var $tableChild = 'tblempchild';
	var $tableChildid = 'childCode';

	var $tableSchool = 'tblempschool';
	var $tableSchoolid = 'levelCode';

	var $tableExam = 'tblempexam';
	var $tableExamid = 'examCode';

	var $tableService = 'tblservicerecord';
	var $tableServiceid = 'serviceRecID';

	var $tableVW = 'tblempvoluntarywork';
	var $tableVWid = 'vwName';

	var $tabletraining = 'tblemptraining';
	var $tabletrainingid = 'XtrainingCode';

	var $tablepos = 'tblempposition';
	var $tableposid = 'appointmentCode';

	var $tableduties = 'tblempduties';
	var $tabledutiesid = 'duties';

	var $tableplantilla = 'tblplantilladuties';
	var $tableplantillaid = 'itemDuties';
	// var $tableplantillaid = 'plantilla_duties_index';

	var $tableCharRef = 'tblempreference';
	var $tableCharRefId = 'ReferenceIndex';

	public function __construct()
	{
		$this->load->database();
		//$this->db->initialize();	
	}
	
	public function add_employee($arrData)
	{
		$this->db->insert($this->table, $arrData);
		return $this->db->insert_id();		
	}

	public function add_employee_status($arrData)
	{
		$this->db->insert($this->tablepos, $arrData);
		return $this->db->insert_id();		
	}
	
	// public function checkExist($strEmpNo)
	// {		
	// 	$objQuery = $this->select('empNumber')->get($this->table);
	// 	return $objQuery->result_array();	
	// }

	public function checkExist($strEmpID = '')
	{		
		$strSQL = " SELECT * FROM tblemppersonal					
					WHERE  
					empNumber ='$strEmpID'
					";
		//echo $strSQL;exit(1);
		$objQuery = $this->db->query($strSQL);
		return $objQuery->result_array();	
	}


	//for QRCode
	public function getData($strEmpNo="",$strSearch="",$strAppStatus="")
	{

		$this->db->select('tblemppersonal.*,tblempposition.*,tblposition.positionDesc,tblappointment.appointmentDesc');
		$where='';
		if($strEmpNo!="")
			$this->db->where('tblemppersonal.empNumber',$strEmpNo);
			//$where .= " AND tblemppersonal.empNumber='".$strEmpNo."'";
		if($strSearch!="")
			$this->db->where("(tblemppersonal.empNumber LIKE '%".$strSearch."%' OR surname LIKE '%".$strSearch."%' OR firstname LIKE '%".$strSearch."%' OR middlename LIKE '%".$strSearch."%' OR nameExtension LIKE '%".$strSearch."%')",NULL,FALSE);
			//$where .= " AND (tblemppersonal.empNumber LIKE '%".$strSearch."%' OR surname LIKE '%".$strSearch."%' OR firstname LIKE '%".$strSearch."%' OR middlename LIKE '%".$strSearch."%')";
		if($strAppStatus=="all"):
			# fetch all services
		elseif($strAppStatus!=""):
			$this->db->where('tblempposition.statusOfAppointment',$strAppStatus);
		else:
			$this->db->where('tblempposition.statusOfAppointment','In-Service');
		endif;
			//$where .= " AND tblempposition.statusOfAppointment='".$strAppStatus."'";
		$this->db->join('tblempposition','tblempposition.empNumber=tblemppersonal.empNumber','left')
		->join('tblposition','tblposition.positionCode=tblempposition.positionCode','left')
		->join('tblappointment','tblappointment.appointmentCode=tblempposition.appointmentCode','left')
		->order_by('surname,firstname,middlename','asc');

		$objQuery = $this->db->get($this->table);
		return $objQuery->result_array();	
	}

	public function get_employee_position($empno)
	{
		return $this->db->get_where('tblempposition',array('empNumber' => $empno))->result_array();
	}

	public function get_employee_number()
	{

		$this->db->select('empNumber');
		$this->db->where('statusOfAppointment','In-Service');

		$res = $this->db->get('tblempposition')->result_array();
		$res = array_column($res, 'empNumber');

		return json_encode($res);
	}

	public function getempby_appointment($apptcode)
	{
		$this->db->order_by('tblemppersonal.surname');
		$this->db->select('tblemppersonal.empNumber,tblemppersonal.surname,tblemppersonal.firstname,tblemppersonal.middlename,tblemppersonal.middleInitial,tblemppersonal.nameExtension,tblempposition.statusOfAppointment');
		$this->db->join('tblemppersonal','tblemppersonal.empNumber = tblempposition.empNumber','left');
		$this->db->where("tblemppersonal.empNumber!=''");
		return $this->db->get_where('tblempposition',array('statusOfAppointment' => 'In-Service', 'appointmentCode' => $apptcode))->result_array();
	}

	public function getData_byGroup($dtrswitch='')
	{
		$this->db->select('tblempposition.empNumber,appointmentCode,statusOfAppointment,positionCode,appointmentCode,
							group1,group2,group3,group4,group5,surname,firstname,middlename,middlename,
							middleInitial,nameExtension');
		$this->db->join('tblempposition','tblempposition.empNumber = tblemppersonal.empNumber','left')
				  ->order_by('surname,firstname','asc');

		if($dtrswitch == ''){
			$this->db->where('dtrSwitch','Y');
		}else{
			$this->db->where('dtrSwitch',$dtrswitch);
		}

		$res = $this->db->get_where($this->table,array('statusOfAppointment' => 'In-Service'))->result_array();
		
		return $res;
	}			

	public function getpayrollprocess($appt='')
 	{
		$sql ="SELECT `processWith` FROM `tblpayrollprocess` WHERE `appointmentCode`='P'";
		 // echo $this->db->last_query();
 		
		$objQuery = $this->db->query($sql);
		return $objQuery->result_array();	
 	}

		
	public function savePersonal($arrData, $strEmpNo)
	{
		$this->db->where($this->tableid,$strEmpNo);
		$this->db->update($this->table, $arrData);
		return $this->db->affected_rows()>0?TRUE:FALSE;
	}

	public function saveSpouse($arrData, $strEmpNo)
	{
		$this->db->where($this->tableChildid,$strEmpNo);
		$this->db->update($this->tableChild, $arrData);
		return $this->db->affected_rows()>0?TRUE:FALSE;
	}

	public function saveParents($arrData, $strEmpNo)
	{
		$this->db->where($this->tableChildid,$strEmpNo);
		$this->db->update($this->tableChild, $arrData);
		return $this->db->affected_rows()>0?TRUE:FALSE;
	}

	public function saveChild($arrData, $strChildCode)
	{
		$this->db->where($this->tableChildid,$strChildCode);
		$this->db->update($this->tableChild, $arrData);
		return $this->db->affected_rows()>0?TRUE:FALSE;
	}

	public function saveEduc($arrData, $strLevelCode)
	{
		$this->db->where($this->tableSchoolid,$strLevelCode);
		$this->db->update($this->tableSchool, $arrData);
		return $this->db->affected_rows()>0?TRUE:FALSE;
	}

	public function saveExam($arrData, $strExamCode)
	{
		$this->db->where($this->tableExamid,$strExamCode);
		$this->db->update($this->tableExam, $arrData);
		return $this->db->affected_rows()>0?TRUE:FALSE;
	}
	
	public function delete($strEmpNo)
	{
		$this->db->where($this->tableid, $strEmpNo);
		$this->db->delete($this->table); 	
		return $this->db->affected_rows()>0?TRUE:FALSE;
	}
		
		
	public function appointment_status($complete=FALSE)
	{
		$objQuery = $this->db->select('tblpayrollprocess.*,tblappointment.appointmentDesc')
					->join('tblappointment','tblpayrollprocess.appointmentCode = tblappointment.appointmentCode','inner')
					->get('tblpayrollprocess');
		$arrData = array();
		if($complete):
			foreach($objQuery->result_array() as $row):
				//echo $row['processWith'];
				$arrAS = explode(',',$row['processWith']);
				if(!is_array($arrAS)):
					//echo "not array=".$row['processWith']."<br>";
					$arrData[]=$row['processWith'];
					$arrData[$row['processWith']]=$row['appointmentCode'];
					//$arrData[]
				else:
					foreach($arrAS as $as):
						//echo "array=".$as."<br>";
						//$arrData[]=$as;
						$arrData[$as]=$as;
					endforeach;
				endif;
				//$arrData[] = $arrAS;
			endforeach;
		else:
			foreach($objQuery->result_array() as $row):
				$arrData[]=$row['appointmentCode'];
			endforeach;
		endif;
		//print_r($arrData);
		return $arrData;
		//print_r($arrData);
	}

	function getEmployeeDetails($strEmpNo,$strSelect,$strTable,$strOrder="",$strJoinTable="",$strJoinString="",$strJoinType="")
	{

		if($strOrder!='')
			$this->db->order_by($strOrder);
		if($strJoinTable!='' && $strJoinString!='' && $strJoinType!='')
			$this->db->join($strJoinTable,$strJoinString,$strJoinType);
		if($strEmpNo!='')
			$this->db->where('empNumber',$strEmpNo);
		$this->db->select($strSelect);
		$rs = $this->db->get($strTable);
		return $rs->result_array();
	}

	function getExam($empid)
	{
		$this->db->order_by('examDate');
		$this->db->join('tblexamtype', 'tblexamtype.examCode = tblempexam.examCode');
		$res = $this->db->get_where('tblempexam', array('empNumber' => $empid))->result_array();
		return $res;
	}

	function getEmployeeEducation($empid)
	{
		$this->db->join('tblscholarship','tblscholarship.id = '.'tblempschool.ScholarshipCode','left');
		return $this->db->get_where('tblempschool', array('empNumber' => $empid))->result_array();
	}

	
	// public function getEmpNumber($intInviteeId="",$intSourceAgency="",$strSearch="",$isVIP="")
	// {
	// 	$strWhere = '';
	// 	if($intInviteeId!='')
	// 		$strWhere .= ' AND '.$this->tableid.'='.$intInviteeId;
	// 	if($intSourceAgency!='')
	// 		$strWhere .= ' AND prf_source_agency_id='.$intSourceAgency;
	// 	if($strSearch!='')
	// 		$strWhere .= " AND (firstname LIKE '%".$strSearch."%' OR middlename LIKE '%".$strSearch."%' OR surname LIKE '%".$strSearch."%')";
	// 	//echo 'isVIP='.$isVIP;
	// 	if($isVIP!='')
	// 	{
	// 	if($isVIP==1)
	// 		$strWhere .= " AND prf_is_vip='Y'";
	// 	//if($isVIP==0)
	// 		//$strWhere .= " AND prf_is_vip='N'";
	// 	}
	//   	$strSQL = "SELECT *  FROM  ".$this->table." 
	// 			  LEFT JOIN `tblclassification` ON `tblinvitees`.`prf_sector_id`=`tblclassification`.`cls_class_id` 
	// 			  LEFT JOIN `tblsourceagency` ON `tblinvitees`.`prf_source_agency_id`=`tblsourceagency`.`sa_agency_id` 
	// 			  WHERE 1=1 ".$strWhere;
	// 	//echo $isVIP;
	// 	//echo $strSQL; exit(1);
	//   $objQuery = $this->db->query( $strSQL );
	//   return $objQuery->result_array();		
	// }

	public function getEmployeePersonal($empid)
	{
		$res = $this->db->get_where($this->table, array($this->tableid => $empid))->result_array();
		return $res[0];
	}

	# BEGIN CHARACTER REFERENCES
	function get_character_references($empNumber)
	{
		return $this->db->get_where($this->tableCharRef, array('empNumber' => $empNumber))->result_array();
	}
	# END CHARACTER REFERENCES

	# BEGIN POSITION DETAILS
	function get_pos_sepMode()
	{
		$this->db->select('distinct(statusOfAppointment) as statusOfAppointment');
		return $this->db->get_where('tblempposition', array('statusOfAppointment!=' => ''))->result_array();
	}

	function get_pos_personnelAction()
	{
		$this->db->select('distinct(personnelAction) as personnelAction');
		return $this->db->get_where('tblempposition', array('personnelAction!=' => ''))->result_array();
	}
	# END POSITION DETAILS

	# BEGIN DUTIES AND RESPONSIBILITIES
	function duties_position($poscode)
	{
		$this->db->order_by('dutyNumber');
		return $this->db->get_where('tblduties', array('positionCode' => $poscode))->result_array();
	}

	function duties_plantilla($itemno)
	{
		$this->db->order_by('dutyNumber');
		return $this->db->get_where('tblplantilladuties', array('itemNumber' => $itemno))->result_array();
		// return $this->db->get_where('tblplantilladuties', array('itemNumber' => $itemno))->result_array();
	}

	function duties_actual($empid)
	{
		return $this->db->get_where('tblempduties', array('empNumber' => $empid))->result_array();
	}
	# END DUTIES AND RESPONSIBILITIES

	function get_appointment_codes(){
		return $this->db->get('tblappointment')->result_array();
	}

	public function get_leave_types()
	{
		return $this->db->get('tblleave')->result_array();
	}

}
/* End of file Hr_model.php */
/* Location: ./application/modules/employees/models/Employees_model.php */
