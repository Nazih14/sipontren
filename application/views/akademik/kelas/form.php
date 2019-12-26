<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Kelas';
$controller = 'akadKelas';
$data = array(
    array(
        'type' => 'select',
        'field' => 'KEGIATAN_KELAS',
        'label' => 'Kegiatan'
    ),
    array(
        'type' => 'text',
        'field' => 'NAMA_KELAS',
        'label' => 'Nama Kelas'
    ),
    array(
        'type' => 'text',
        'field' => 'KETERANGAN_KELAS',
        'label' => 'Keterangan Kelas'
    ),
    array(
        'type' => 'text',
        'field' => 'KODE_EMIS_KELAS',
        'label' => 'Kode EMIS'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>