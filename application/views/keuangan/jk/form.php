<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Jk';
$controller = 'mdJk';
$data = array(
    array(
        'type' => 'text',
        'field' => 'NAMA_JK',
        'label' => 'Nama Jenis Kelamin'
    ),
    array(
        'type' => 'text',
        'field' => 'KODE_EMIS_JK',
        'label' => 'Kode EMIS'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>