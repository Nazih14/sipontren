<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Tempat Tinggal';
$controller = 'mdTempatTinggal';
$data = array(
    array(
        'type' => 'text',
        'field' => 'NAMA_TEMTING',
        'label' => 'Nama Tempat Tinggal'
    ),
    array(
        'type' => 'text',
        'field' => 'KODE_EMIS_TEMTING',
        'label' => 'Kode EMIS'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>