<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Kabupaten';
$controller = 'mdKabupaten';
$data = array(
    array(
        'type' => 'autocomplete',
        'field' => 'PROVINSI_KAB',
        'label' => 'Provinsi'
    ),
    array(
        'type' => 'text',
        'field' => 'NAMA_KAB',
        'label' => 'Nama Kabupaten'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>