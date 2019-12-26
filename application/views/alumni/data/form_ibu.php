<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Data Ibu Santri';
$controller = 'dataSantri';
$data = array(
    array(
        'type' => 'number',
        'field' => 'IBU_NIK_SANTRI',
        'label' => 'NIK Ibu',
        'required' => FALSE
    ),
    array(
        'type' => 'text',
        'field' => 'IBU_NAMA_SANTRI',
        'label' => 'Nama Ibu'
    ),
    array(
        'type' => 'select',
        'field' => 'IBU_HIDUP_SANTRI',
        'label' => 'Status Ibu',
        'required' => FALSE
    ),
    array(
        'type' => 'text',
        'field' => 'IBU_TEMPAT_LAHIR_SANTRI',
        'label' => 'Tempat Lahir Ibu',
        'required' => FALSE
    ),
    array(
        'type' => 'date',
        'field' => 'IBU_TANGGAL_LAHIR_SANTRI',
        'label' => 'Tanggal Lahir Ibu',
        'required' => FALSE
    ),
    array(
        'type' => 'select',
        'field' => 'IBU_PENDIDIKAN_SANTRI',
        'label' => 'Pendidikan Ibu',
        'required' => FALSE
    ),
    array(
        'type' => 'select',
        'field' => 'IBU_PEKERJAAN_SANTRI',
        'label' => 'Pekerjaan Ibu',
        'required' => FALSE
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>