<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Status Hidup';
$controller = 'mdStatusHidup';
$data = array(
    array(
        'type' => 'text',
        'field' => 'NAMA_SO',
        'label' => 'Nama Status Hidup'
    ),
    array(
        'type' => 'text',
        'field' => 'KODE_EMIS_SO',
        'label' => 'Kode EMIS'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>