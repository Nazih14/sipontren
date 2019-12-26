<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Penghasilan';
$controller = 'mdPenghasilan';
$data = array(
    array(
        'type' => 'text',
        'field' => 'NAMA_HASIL',
        'label' => 'Nama Penghasilan'
    ),
    array(
        'type' => 'text',
        'field' => 'KODE_EMIS_HASIL',
        'label' => 'Kode EMIS'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>