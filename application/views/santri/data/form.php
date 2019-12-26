<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Data Diri Santri';
$controller = 'dataSantri';
$data = array(
    array(
        'type' => 'text',
        'field' => 'NIS_SANTRI',
        'label' => 'NIS'
    ),
    array(
        'type' => 'number',
        'field' => 'NIK_SANTRI',
        'label' => 'NIK'
    ),
    array(
        'type' => 'text',
        'field' => 'NAMA_SANTRI',
        'label' => 'Nama Santri'
    ),
    array(
        'type' => 'text',
        'field' => 'PANGGILAN_SANTRI',
        'label' => 'Nama Panggilan Santri',
        'required' => FALSE
    ),
    array(
        'type' => 'number',
        'field' => 'ANGKATAN_SANTRI',
        'label' => 'Angkatan'
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
        'field' => 'EMAIL_SANTRI',
        'label' => 'Email',
        'required' => FALSE
    ),
    array(
        'type' => 'select',
        'field' => 'STATUS_ASAL_SANTRI',
        'label' => 'Asal Santri',
        'required' => FALSE
    ),
    array(
        'type' => 'select',
        'field' => 'AGAMA_SANTRI',
        'label' => 'Agama',
        'required' => FALSE
    ),
    array(
        'type' => 'select',
        'field' => 'SUKU_SANTRI',
        'label' => 'Suku',
        'required' => FALSE
    ),
    array(
        'type' => 'select',
        'field' => 'KONDISI_SANTRI',
        'label' => 'Kondisi',
        'required' => FALSE
    ),
    array(
        'type' => 'number',
        'field' => 'ANAK_KE_SANTRI',
        'label' => 'Anak ke',
        'required' => FALSE
    ),
    array(
        'type' => 'number',
        'field' => 'JUMLAH_SDR_SANTRI',
        'label' => 'Jumlah Saudara',
        'required' => FALSE
    ),
    array(
        'type' => 'number',
        'field' => 'BERAT_SANTRI',
        'label' => 'Berat Badan',
        'required' => FALSE
    ),
    array(
        'type' => 'number',
        'field' => 'TINGGI_SANTRI',
        'label' => 'Tinggi Badan',
        'required' => FALSE
    ),
    array(
        'type' => 'select',
        'field' => 'GOL_DARAH_SANTRI',
        'label' => 'Golongan Darah',
        'required' => FALSE
    ),
    array(
        'type' => 'text',
        'field' => 'RIWAYAT_KESEHATAN_SANTRI',
        'label' => 'Riwayat Kesehatan',
        'required' => FALSE
    ),
    array(
        'type' => 'text',
        'field' => 'NO_IJASAH_SANTRI',
        'label' => 'Nomor Ijasah',
        'required' => FALSE
    ),
    array(
        'type' => 'date',
        'field' => 'TANGGAL_IJASAH_SANTRI',
        'label' => 'Tamggal Ijasah',
        'required' => FALSE
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>