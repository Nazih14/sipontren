<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Calon Santri';
$controller = 'dataPSB';
$data = array(
    array(
        'type' => 'select',
        'field' => 'PSB_KELOMPOK_SANTRI',
        'label' => 'Kelompok'
    ),
    array(
        'type' => 'text',
        'field' => 'NAMA_SANTRI',
        'label' => 'Nama Santri'
    ),
    array(
        'type' => 'select',
        'field' => 'JK_SANTRI',
        'label' => 'Jenis Kelamin'
    ),
    array(
        'type' => 'text',
        'field' => 'TEMPAT_LAHIR_SANTRI',
        'label' => 'Tempat Lahir'
    ),
    array(
        'type' => 'date',
        'field' => 'TANGGAL_LAHIR_SANTRI',
        'label' => 'Tanggal Lahir'
    ),
    array(
        'type' => 'text',
        'field' => 'ALAMAT_SANTRI',
        'label' => 'Alamat'
    ),
    array(
        'type' => 'autocomplete',
        'field' => 'KECAMATAN_SANTRI',
        'label' => 'Kecamatan'
    ),
    array(
        'type' => 'text',
        'field' => 'NOHP_SANTRI',
        'label' => 'No HP'
    ),
    array(
        'type' => 'text',
        'field' => 'AYAH_NAMA_SANTRI',
        'label' => 'Nama Ayah'
    ),
    array(
        'type' => 'checkbox',
        'field' => 'KEGIATAN_SANTRI',
        'label' => 'Kelas Santri',
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>