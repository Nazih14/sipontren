<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Hubungan Wali dengan Santri';
$controller = 'mdHubungan';
$data = array(
    array(
        'type' => 'text',
        'field' => 'NAMA_HUB',
        'label' => 'Nama Hubungan'
    ),
    array(
        'type' => 'text',
        'field' => 'KODE_EMIS_HUB',
        'label' => 'Kode EMIS'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>