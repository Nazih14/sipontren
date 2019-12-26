<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Tahun Ajaran';
$controller = 'mdTA';
$data = array(
    array(
        'type' => 'text',
        'field' => 'NAMA_TA',
        'label' => 'Nama TA'
    ),
    array(
        'type' => 'date',
        'field' => 'TANGGAL_MULAI_TA',
        'label' => 'Tanggal Mulai'
    ),
    array(
        'type' => 'date',
        'field' => 'TANGGAL_AKHIR_TA',
        'label' => 'Tanggal Akhir'
    ),
    array(
        'type' => 'select',
        'field' => 'AKTIF_TA',
        'label' => 'Status Aktif'
    ),
    array(
        'type' => 'text',
        'field' => 'KETERANGAN_TA',
        'label' => 'Keterangan'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>