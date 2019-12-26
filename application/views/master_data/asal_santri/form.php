<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Asal Santri';
$controller = 'mdAsalSantri';
$data = array(
    array(
        'type' => 'text',
        'field' => 'NAMA_ASSAN',
        'label' => 'Nama Asal Santri'
    ),
    array(
        'type' => 'text',
        'field' => 'KODE_EMIS_ASSAN',
        'label' => 'Kode EMIS'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>