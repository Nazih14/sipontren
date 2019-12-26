<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Provinsi';
$controller = 'mdProvinsi';
$data = array(
    array(
        'type' => 'text',
        'field' => 'NAMA_PROV',
        'label' => 'Nama Provinsi'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>