<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Pekerjaan';
$controller = 'mdPekerjaan';
$data = array(
    array(
        'type' => 'text',
        'field' => 'NAMA_JENPEK',
        'label' => 'Nama Pekerjaan'
    ),
    array(
        'type' => 'text',
        'field' => 'KODE_EMIS_JENPEK',
        'label' => 'Kode EMIS'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>