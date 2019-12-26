<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Jurusan';
$controller = 'mdJurusan';
$data = array(
    array(
        'type' => 'text',
        'field' => 'NAMA_JURUSAN',
        'label' => 'Nama Jurusan'
    ),
    array(
        'type' => 'text',
        'field' => 'KODE_EMIS_JURUSAN',
        'label' => 'Kode EMIS'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>