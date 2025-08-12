<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SessionCheck
{
    public function check_sess_empno()
    {
        $CI =& get_instance();
        $CI->load->library('session');
        $CI->load->helper('url');

        // Only check for session if controller is not login
        $class = $CI->router->fetch_class();
        if (!in_array($class, ['login', 'auth'])) {
            $empNo = $CI->session->userdata('sessEmpNo');

            if (!$empNo) {
                // Redirect to login if session is missing
                redirect('login');
                exit;
            }
        }
    }
}
