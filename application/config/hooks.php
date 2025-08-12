<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['pre_system'] = function() {
    $dotenv = new Dotenv\Dotenv(FCPATH);
    $dotenv->load();
};

/**
 * Check for sessEmpNo before any controller runs
 */
$hook['pre_controller'][] = array(
    'class'    => 'SessionCheck',
    'function' => 'check_sess_empno',
    'filename' => 'SessionCheck.php',
    'filepath' => 'hooks',
    'params'   => array()
);

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */
