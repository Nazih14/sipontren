<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Status Keluar';
$controller = 'mdStatusKeluar';
$data = array(
    array(
        'type' => 'text',
        'field' => 'NAMA_MUTASI',
        'label' => 'Nama Status Keluar'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>