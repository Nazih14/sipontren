<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Data Diri Ustadz';
$controller = 'dataUstadz';
$data = array(
    array(
        'type' => 'number',
        'field' => 'NIP_UST',
        'label' => 'NIP'
    ),
    array(
        'required' => FALSE,
        'type' => 'number',
        'field' => 'NIK_UST',
        'label' => 'NIK'
    ),
    array(
        'type' => 'text',
        'field' => 'NAMA_UST',
        'label' => 'Nama Ustadz'
    ),
    array(
        'required' => FALSE,
        'type' => 'select',
        'field' => 'ROMBEL_UST',
        'label' => 'Wali Kelas untuk Rombel'
    ),
    array(
        'required' => FALSE,
        'type' => 'text',
        'field' => 'GELAR_AWAL_UST',
        'label' => 'Gelar Awal Ustadz'
    ),
    array(
        'required' => FALSE,
        'type' => 'text',
        'field' => 'GELAR_AKHIR_UST',
        'label' => 'Gelar Akhir Ustadz'
    ),
    array(
        'type' => 'select',
        'field' => 'JK_UST',
        'label' => 'Jenis Kelamin'
    ),
    array(
        'type' => 'text',
        'field' => 'TEMPAT_LAHIR_UST',
        'label' => 'Tempat Lahir'
    ),
    array(
        'type' => 'date',
        'field' => 'TANGGAL_LAHIR_UST',
        'label' => 'Tanggal Lahir'
    ),
    array(
        'type' => 'text',
        'field' => 'ALAMAT_UST',
        'label' => 'Alamat'
    ),
    array(
        'type' => 'autocomplete',
        'field' => 'KECAMATAN_UST',
        'label' => 'Kecamatan'
    ),
    array(
        'type' => 'text',
        'field' => 'NOHP_UST',
        'label' => 'No HP',
        'required' => FALSE
    ),
    array(
        'type' => 'text',
        'field' => 'EMAIL_UST',
        'label' => 'Email',
        'required' => FALSE
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>