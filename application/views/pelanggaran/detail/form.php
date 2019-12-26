<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Pelanggaran';
$controller = 'pelanggaranDetail';
$data = array(
    array(
        'type' => 'date',
        'field' => 'TANGGAL_PSN',
        'label' => 'Tanggal Pelanggaran'
    ),
    array(
        'type' => 'autocomplete',
        'field' => 'JENIS_PSN',
        'label' => 'Jenis Pelanggaran'
    ),
    array(
        'type' => 'autocomplete',
        'field' => 'SANTRI_PSN',
        'label' => 'Nama Santri'
    ),
    array(
        'type' => 'text',
        'field' => 'KETERANGAN_PSN',
        'label' => 'Keterangan'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>