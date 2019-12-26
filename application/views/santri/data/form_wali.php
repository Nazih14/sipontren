<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Data Wali Santri';
$controller = 'dataSantri';
$data = array(
    array(
        'type' => 'number',
        'field' => 'WALI_NIK_SANTRI',
        'label' => 'NIK Wali',
        'required' => FALSE
    ),
    array(
        'type' => 'text',
        'field' => 'WALI_NAMA_SANTRI',
        'label' => 'Nama Wali'
    ),
    array(
        'type' => 'select',
        'field' => 'WALI_HUBUNGAN_SANTRI',
        'label' => 'Hubungan',
        'required' => FALSE
    ),
    array(
        'type' => 'select',
        'field' => 'WALI_PENDIDIKAN_SANTRI',
        'label' => 'Pendidikan Wali',
        'required' => FALSE
    ),
    array(
        'type' => 'select',
        'field' => 'WALI_PEKERJAAN_SANTRI',
        'label' => 'Pekerjaan Wali',
        'required' => FALSE
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>