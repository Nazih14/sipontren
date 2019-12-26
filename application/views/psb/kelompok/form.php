<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Kelompok';
$controller = 'psbKelompok';
$data = array(
    array(
        'type' => 'text',
        'field' => 'NAMA_PKK',
        'label' => 'Nama Kelompok'
    ),
    array(
        'type' => 'text',
        'field' => 'KETERANGAN_PKK',
        'label' => 'Keterangan Kelompok'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>