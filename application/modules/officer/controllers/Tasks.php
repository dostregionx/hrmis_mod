<?php

/**
 * SystemName: Human Resoruce Management System
 *
 * Author: Maychell M. Alcorin
 *
 * Copyright (C) 2018 by the Department of Science and Technology Central Office
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Tasks extends MY_Controller
{

        var $arrData;

        function __construct()
        {
                parent::__construct();
                $this->load->model(array('libraries/Position_model', 'libraries/Request_model', 'employee/Notification_model'));
        }

        public function index()
        {
                $officer_empid = $this->session->userdata('sessEmpNo');
                $office = employee_office($officer_empid);
                $emps = $this->Position_model->getemployees_byoffice($office);
                $emps = array_column($emps, 'empNumber');

                // Default month to 'all' if no GET parameter exists in URL
                $strYear  = $this->input->get('yr') ? $this->input->get('yr') : curryr();
                $strMonth = $this->input->get('month') !== null ? $this->input->get('month') : 'all';

                $requestFlow = $this->Request_model->getRequestFlow($officer_empid);

                $allemp_request = array();

                // Pass empty string if 'all' to prevent filtering by month in model
                $dbMonth = ($strMonth === 'all' || $strMonth === '') ? '' : sprintf('%02d', $strMonth);
                $arremp_request = $this->Request_model->getEmployeeRequest($strYear, $dbMonth);
                $arrRequest = $this->Notification_model->check_request_flow_and_signatories2($requestFlow, $arremp_request, $office);

                foreach ($arrRequest as $req) :

                        if ((strpos($req['req_nextsign'], $officer_empid) !== false)) :
                                $req['req_desti'] = $this->Notification_model->getDestination($req['req_nextsign']);
                                $req['req_empname'] = employee_name_formal($req['req_emp']);
                                array_push($allemp_request, $req);
                        endif;
                endforeach;

                $this->arrData['strYear']  = $strYear;
                $this->arrData['strMonth'] = $strMonth;
                $this->arrData['allemp_request'] = $allemp_request;
                $this->template->load('template/template_view', 'officer/tasks', $this->arrData);
        }

        // FUNCTION FOR RD
        public function task_executive()
        {
                $officer_empid = $this->session->userdata('sessEmpNo');
                $office = employee_office($officer_empid);

                // Default month to 'all' if no GET parameter exists in URL
                $strYear  = $this->input->get('yr') ? $this->input->get('yr') : curryr();
                $strMonth = $this->input->get('month') !== null ? $this->input->get('month') : 'all';

                $requestFlow = $this->Request_model->getRequestFlow($officer_empid, $office);

                $allemp_request = array();

                // Pass empty string if 'all' to prevent filtering by month in model
                $dbMonth = ($strMonth === 'all' || $strMonth === '') ? '' : sprintf('%02d', $strMonth);
                $arremp_request = $this->Request_model->getEmployeeRequest($strYear, $dbMonth);
                $arrRequest = $this->Notification_model->check_request_flow_and_signatories($requestFlow, $arremp_request);

                foreach ($arrRequest as $req) :
                        if ((strpos($req['req_nextsign'], $officer_empid) !== false)) :
                                $req['req_desti'] = $this->Notification_model->getDestination($req['req_nextsign']);
                                $req['req_empname'] = employee_name($req['req_emp']);
                                array_push($allemp_request, $req);
                        endif;
                endforeach;

                $this->arrData['strYear']  = $strYear;
                $this->arrData['strMonth'] = $strMonth;
                $this->arrData['allemp_request'] = $allemp_request;
                $this->template->load('template/template_view', 'officer/tasks', $this->arrData);
        }
}
