<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Jenjang Pendidikan';
$controller = 'mdJenjangPendidikan';
$data = array(
    array(
        'type' => 'text',
        'field' => 'NAMA_JP',
        'label' => 'Nama Jenjang Pendidikan'
    ),
    array(
        'type' => 'text',
        'field' => 'KODE_EMIS_JP',
        'label' => 'Kode EMIS'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>