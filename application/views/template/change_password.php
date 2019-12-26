<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Password';
$controller = 'changePassword';
$data = array(
    array(
        'type' => 'password',
        'field' => 'NEW_PASSWORD',
        'label' => 'Password Baru'
    ),
    array(
        'type' => 'password',
        'field' => 'NEW_REPASSWORD',
        'label' => 'Ulangi Password Baru'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>